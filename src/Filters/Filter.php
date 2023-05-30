<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filters;

use CannaPress\Server\Models\FilterTerm;
use Throwable;

class Filter
{
    public const type_and = 'and';
    public const type_or = 'or';
    public const type_not = 'not';
    public const type_eq = '==';
    public const type_neq = '<>';
    public const type_lt = '<';
    public const type_lte = '<=';
    public const type_gt = '>';
    public const type_gte = '>=';
    public const type_like = 'like';
    public const type_in = 'in';
    public const type_not_like = 'not_like';
    public const type_not_in = 'not_in';
    public const type_is_null = 'is_null';
    public const type_is_not_null = 'is_not_null';
    public const type_value = FilterToken::type_value;
    public const type_attribute = FilterToken::type_attribute;

    //order of this array is important to the tokenizer

    public static $operators = [
        self::type_is_not_null => 1, self::type_is_null => 1,
        self::type_in => 2,  self::type_not_in => 2,
        self::type_like => 2, self::type_not_like => 2,
        self::type_not => 1,
        self::type_eq => 2, self::type_neq => 2,
        self::type_gte => 2, self::type_lte => 2,
        self::type_gt => 2, self::type_lt => 2,
        self::type_and => 2, self::type_or => 2
    ];


    public function serialize(): array
    {
        return ['(',  $this->type, ...array_map(fn ($x) => $x->serialize(), $this->operands), ')'];
    }
    protected function __construct(public FilterToken $token, public string $type, public array $operands)
    {
    }

    private static function compiled_filter(Filter $filter, callable $invocation)
    {
        return new class($filter) implements FilterPredicate
        {
            public function __construct(private Filter $filter, private $invocation)
            {
            }
            public function __invoke(FilterValueAccessor $entryRef)
            {
                return ($this->invocation)($entryRef);
            }
        };
    }
    private static $compiled_like_cache = [];

    private static function convert_like_to_regex($clause)
    {
        if (!in_array($clause, Filter::$compiled_like_cache)) {
            $p = 0;
            $len = strlen($clause);
            $result = '';
            while ($p < $len) {
                if (preg_match('/^(%%?)/',  substr($clause, $p), $matches) !== false) {
                    if (strlen($matches[0]) === 1) {
                        $result .= '.*';
                    } else {
                        $result += $matches[0];
                    }
                    $p += strlen($matches[0]);
                }
                //matches _ accounting for double-_ escaping
                else if (preg_match('/^(__?)/', substr($clause, $p), $matches) !== false) {
                    if (strlen($matches[0]) === 1) {
                        $result .= '.';
                    } else {
                        $result += $matches[0];
                    }
                    $p += strlen($matches[0]);
                }
                //matches anything else
                else if (preg_match('/^([^%_]+)/', substr($clause, $p), $matches) !== false) {
                    $result .= preg_quote($matches[0]);
                    $p += strlen($matches[0]);
                } else {
                    throw new FilterException("Invalid like expression \"$clause\", unexpected sequence at $p", ['clause' => $clause, 'position' => $p]);
                }
            }
            Filter::$compiled_like_cache[$clause] = '/' . $result . '/';
        }
        return Filter::$compiled_like_cache[$clause];
    }

    public static function compile(Filter $filter): FilterPredicate
    {

        if ($filter->type === Filter::type_attribute) {
            return self::compiled_filter($filter, fn ($f, $e) => $e->get($f->operands[0]));
        } else if ($filter->type === Filter::type_value) {
            return self::compiled_filter($filter, fn ($f, $e) => $f->operands[0]);
        }
        $compiled_operands = array_map(fn ($x) => self::compile($x), $filter->operands);

        if ($filter->type === Filter::type_in) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $needle = $compiled_operands[0]->__invoke($entryRef);
                $haystack = array_map(fn ($x) => $x->__invoke($entryRef), array_slice($compiled_operands, 1));
                return in_array($needle, $haystack);
            });
        } else if ($filter->type === Filter::type_not_in) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $needle = $compiled_operands[0]->__invoke($entryRef);
                $haystack = array_map(fn ($x) => $x->__invoke($entryRef), array_slice($compiled_operands, 1));
                return !in_array($needle, $haystack);
            });
        } else if ($filter->type === Filter::type_is_not_null) {

            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                return isset($compiled_operands[0]->__invoke($entryRef));
            });
        } else if ($filter->type === Filter::type_is_null) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                return !isset($compiled_operands[0]->__invoke($entryRef));
            });
        } else if ($filter->type === Filter::type_like) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands, $filter) {
                $lhs = $compiled_operands[0]->__invoke($entryRef);

                try {
                    $rhs =  Filter::convert_like_to_regex($compiled_operands[1]->__invoke($entryRef));
                    return preg_match($rhs, $lhs) !== false;
                } catch (Throwable $ex) {
                    throw new FilterException("Error evaluating like: " . $ex->getMessage(), [
                        'filter' => $filter->serialize(),
                        'at' => $filter->token->index,
                        'code' => $ex->getCode(),
                    ]);
                }
            });
        } else if ($filter->type === Filter::type_not_like) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands, $filter) {
                $lhs = $compiled_operands[0]->__invoke($entryRef);
                try {
                    $rhs =  Filter::convert_like_to_regex($compiled_operands[1]->__invoke($entryRef));
                    return preg_match($rhs, $lhs) === false;
                } catch (Throwable $ex) {
                    throw new FilterException("Error evaluating like: " . $ex->getMessage(), [
                        'filter' => $filter->serialize(),
                        'at' => $filter->token->index,
                        'code' => $ex->getCode(),
                    ]);
                }
            });
        } else if ($filter->type === Filter::type_gte) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $lhs = $compiled_operands[0]->__invoke($entryRef);
                $rhs = $compiled_operands[1]->__invoke($entryRef);
                if (is_null($lhs) || is_null($rhs)) {
                    return false;
                }
                return strcmp($lhs, $rhs) >= 0;
            });
        } else if ($filter->type === Filter::type_gt) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $lhs = $compiled_operands[0]->__invoke($entryRef);
                $rhs = $compiled_operands[1]->__invoke($entryRef);
                if (is_null($lhs) || is_null($rhs)) {
                    return false;
                }
                return strcmp($lhs, $rhs) > 0;
            });
        } else if ($filter->type === Filter::type_lte) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $lhs = $compiled_operands[0]->__invoke($entryRef);
                $rhs = $compiled_operands[1]->__invoke($entryRef);
                if (is_null($lhs) || is_null($rhs)) {
                    return false;
                }
                return strcmp($lhs, $rhs) <= 0;
            });
        } else if ($filter->type === Filter::type_lt) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $lhs = $compiled_operands[0]->__invoke($entryRef);
                $rhs = $compiled_operands[1]->__invoke($entryRef);
                if (is_null($lhs) || is_null($rhs)) {
                    return false;
                }
                return strcmp($lhs, $rhs) < 0;
            });
        } else if ($filter->type === Filter::type_neq) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $lhs = $compiled_operands[0]->__invoke($entryRef);
                $rhs = $compiled_operands[1]->__invoke($entryRef);
                if (is_null($lhs) || is_null($rhs)) {
                    return false;
                }
                return strcmp($lhs, $rhs) !== 0;
            });
        } else if ($filter->type === Filter::type_eq) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $lhs = $compiled_operands[0]->__invoke($entryRef);
                $rhs = $compiled_operands[1]->__invoke($entryRef);
                if (is_null($lhs) || is_null($rhs)) {
                    return false;
                }
                return strcmp($lhs, $rhs) === 0;
            });
        } else if ($filter->type === Filter::type_not) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $op = $compiled_operands[0]->__invoke($entryRef);
                if (!is_bool($op)) {
                    return false;
                }
                return !($op);
            });
        } else if ($filter->type === Filter::type_or) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $lhs = $compiled_operands[0]->__invoke($entryRef);
                $rhs = $compiled_operands[1]->__invoke($entryRef);
                if (is_null($lhs) || is_null($rhs)) {
                    return false;
                }
                return $lhs || $rhs;
            });
        } else if ($filter->type === Filter::type_and) {
            return self::compiled_filter($filter, function (FilterValueAccessor $entryRef) use ($compiled_operands) {
                $lhs = $compiled_operands[0]->__invoke($entryRef);
                $rhs = $compiled_operands[1]->__invoke($entryRef);
                if (is_null($lhs) || is_null($rhs)) {
                    return false;
                }
                return $lhs && $rhs;
            });
        }
    }

    public static function value(FilterToken $token)
    {
        return new class(Filter::type_value, [$token->value]) extends Filter
        {
            public function serialize(): array
            {
                return ['\'' . str_replace('\'', '\'\'', $this->operands[0]) . '\''];
            }
        };
    }
    public static function attribute(FilterToken $token)
    {
        return new class(Filter::type_attribute, [$token->value]) extends Filter
        {
            public function serialize(): array
            {
                return [$this->operands[0]];
            }
        };
    }

    public static function parseUrlEncodedArray(string|array $filter)
    {
        if (is_string($filter)) {
            if (str_starts_with($filter, '\'')) {
                return self::value(new FilterToken(FilterToken::type_value, str_replace('\'\'', '\'', substr($filter, 1, strlen($filter) - 2)), 0));
            } else {
                return self::value(new FilterToken(FilterToken::type_value, $filter, 0));
            }
        }
        if (!isset($filter['o'])) {
            throw new FilterException("unable to parse filter, missing operator at " . http_build_query($filter), ['filter' => $filter]);
        }
        $type = trim(strtolower($filter['o']));
        if ($type === self::type_is_not_null || $type === self::type_is_null || $type === self::type_not) {
            if (!isset($filter[0]) && !isset($filter['v'])) {
                throw new FilterException("unable to parse filter, missing argument at " . http_build_query($filter), ['filter' => $filter]);
            }
            return new Filter(
                new FilterToken(FilterToken::type_operator, $type, 0),
                $type,
                [self::parseUrlEncodedArray(isset($filter[0]) ? $filter[0] : $filter[0])]
            );
        } else {
            $lhs = isset($filter[0]) ? $filter[0] : (isset($filter['l']) ? $filter['l'] : null);
            $rhs = isset($filter[1]) ? $filter[1] : (isset($filter['r']) ? $filter['r'] : null);
            if (is_null($lhs) || is_null($rhs)) {
                throw new FilterException("unable to parse filter, missing argument at " . http_build_query($filter), ['filter' => $filter]);
            }
            return new Filter(
                new FilterToken(FilterToken::type_operator, $type, 0),
                $type,
                [self::parseUrlEncodedArray($lhs), self::parseUrlEncodedArray($rhs)]
            );
        }
    }

    public static function parser(array $tokens)
    {
        $stack = [];
        $output = [];
        $i = 0;
        $len = count($tokens);
        while (count($tokens) > 0) {
            $t = array_shift($tokens);
            if ($t->type === FilterToken::type_rparen) {
                $subexp = [];
                $stackItem = array_pop($stack);
                while (is_array($stackItem) || $stack[0]->type !== FilterToken::type_lparen) {
                    array_push($subexp, $stackItem);
                }
                array_push($stack, $subexp);
            } else {
                array_push($t);
            }
        }
        if (count($stack) !== 1) {
            throw new FilterException("error parsing tokens -- incorrect stack count", []);
        }
        return self::construct_filters($stack[0]);
        // at this our stack should contain a nested array of expressions in the form [$op ...$terms];
    }
    private static function construct_filters(array $s_expr): Filter
    {
        if (count($s_expr) === 1) { // either a value or attribute
            if ($s_expr[0]->type === FilterToken::type_attribute) {
                return self::attribute($s_expr[0]);
            } else {
                return self::value($s_expr[0]);
            }
        } else {
            $operator = array_shift($s_expr);
            return new Filter($operator, $operator->type, array_map(fn ($x) => Filter::construct_filters($x), $s_expr));
        }
    }
}

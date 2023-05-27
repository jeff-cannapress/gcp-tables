<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;


use Exception;
use InvalidArgumentException;


abstract class FilterPredicate
{
    public abstract function isIdOnly(): bool;
    public abstract function __invoke(EntryRef $entry): bool;
    public abstract function allProps(): array;
    public static function parse(array $filter_params): FilterPredicate
    {
        throw new \Exception("Not Implemented");
    }
    private static function logic(string $name, array $filters, callable $invoker): FilterPredicate
    {
        return new class($name, $filters) extends FilterPredicate
        {
            public function __construct(private string $name, private array $filters, private $invoker)
            {
                if (empty($this->filters)) {
                    throw new InvalidArgumentException("\$filters may not be empty");
                }
            }
            public function isIdOnly(): bool
            {
                return array_reduce($this->filters, fn ($c, $i) => $c && $i->isIdOnly(), true);
            }
            public function __invoke(EntryRef $entry): bool
            {
                return ($this->invoker)($entry, $this->filters);
            }
            public function allProps(): array
            {
                $props = [];
                foreach ($this->filters as $filter) {
                    $props = array_merge($props, $filter->allProps());
                }
                return array_unique($props);
            }
        };
    }
    public static function and(string $name, array $filters): FilterPredicate
    {
        return self::logic($name, $filters, function (EntryRef $entry, array $filters) {
            foreach ($filters as $filter) {
                if (!$filter($entry)) {
                    return false;
                }
            }
            return true;
        });
    }
    public static function or(string $name, array $filters): FilterPredicate
    {
        return self::logic($name, $filters, function (EntryRef $entry, array $filters) {
            foreach ($filters as $filter) {
                if (!$filter($entry)) {
                    return false;
                }
            }
            return true;
        });
    }
    public static function not(string $name, array $filters): FilterPredicate
    {
        return self::logic($name, $filters, function (EntryRef $entry, array $filters) {
            return !($filters[0]($entry));
        });
    }
    public static function eq(string $name, FilterTerm $lhs, FilterTerm $rhs)
    {
        return self::comparison($name, $lhs, $rhs, function (string $a, string $b) {
            return strcmp($a, $b) === 0;
        });
    }
    public static function neq(string $name, FilterTerm $lhs, FilterTerm $rhs)
    {
        return self::comparison($name, $lhs, $rhs, function (string $a, string $b) {
            return strcmp($a, $b) !== 0;
        });
    }
    public static function lt(string $name, FilterTerm $lhs, FilterTerm $rhs)
    {
        return self::comparison($name, $lhs, $rhs, function (string $a, string $b) {
            return strcmp($a, $b) < 0;
        });
    }
    public static function lte(string $name, FilterTerm $lhs, FilterTerm $rhs)
    {
        return self::comparison($name, $lhs, $rhs, function (string $a, string $b) {
            return strcmp($a, $b) <= 0;
        });
    }
    public static function gt(string $name, FilterTerm $lhs, FilterTerm $rhs)
    {
        return self::comparison($name, $lhs, $rhs, function (string $a, string $b) {
            return strcmp($a, $b) > 0;
        });
    }
    public static function gte(string $name, FilterTerm $lhs, FilterTerm $rhs)
    {
        return self::comparison($name, $lhs, $rhs, function (string $a, string $b) {
            return strcmp($a, $b) >= 0;
        });
    }

    public static function like(string $name, FilterTerm $lhs, FilterTerm $rhs)
    {
        return self::comparison($name, $lhs, $rhs, function (string $a, string $b) {
            return strcmp($a, $b) >= 0;
        });
    }

    public static function between(string $name, FilterTerm $lowerBound, FilterTerm $operand, FilterTerm $upperBound)
    {
        return new class($name, $lowerBound, $operand, $upperBound) extends FilterPredicate
        {
            public function __construct(private string $name, private FilterTerm $lowerBound, private FilterTerm $operand, private FilterTerm $upperBound)
            {
            }
            public function isIdOnly(): bool
            {
                return $this->lowerBound->isIdOnly() && $this->operand->isIdOnly() && $this->upperBound->isIdOnly();
            }
            public function __invoke(EntryRef $entry)
            {
                $lower = ($this->lowerBound)($entry);
                $operand = ($this->upperBound)($entry);
                $upper = ($this->upperBound)($entry);
                return strcmp($lower, $operand) <= 0 && 0 <= strcmp($operand, $upper);
            }
            public function allProps(): array
            {
                $props = [];
                array_merge($props, $this->lowerBound->propNames());
                array_merge($props, $this->operand->propNames());
                array_merge($props, $this->upperBound->propNames());
                return array_unique($props);
            }
        };
    }
    public static function in(string $name, FilterTerm $operand, array $terms)
    {
        return new class($name, $operand, $terms) extends FilterPredicate
        {
            public function __construct(private string $name, private FilterTerm $operand, private array $terms)
            {
            }
            public function isIdOnly(): bool
            {
                return $this->operand->isIdOnly() &&  array_reduce($this->terms, fn ($c, $i) => $c && $i->isIdOnly(), true);
            }
            public function __invoke(EntryRef $entry)
            {
                $haystack = array_map(fn ($term) => ($term)($entry), $this->terms);
                $needle = ($this->operand)($entry);
                return in_array($needle, $haystack);
            }
            public function allProps(): array
            {
                $props = [];
                array_merge($props, $this->operand->propNames());
                foreach ($this->terms as $term) {
                    array_merge($props, $term->propNames());
                }
                return array_unique($props);
            }
        };
    }
    public static function not_in(string $name, FilterTerm $operand, array $terms)
    {
        return new class($name, $operand, $terms) extends FilterPredicate
        {
            public function __construct(private string $name, private FilterTerm $operand, private array $terms)
            {
            }
            public function isIdOnly(): bool
            {
                return $this->operand->isIdOnly() &&  array_reduce($this->terms, fn ($c, $i) => $c && $i->isIdOnly(), true);
            }
            public function __invoke(EntryRef $entry)
            {
                $haystack = array_map(fn ($term) => ($term)($entry), $this->terms);
                $needle = ($this->operand)($entry);
                return in_array($needle, $haystack);
            }
            public function allProps(): array
            {
                $props = [];
                array_merge($props, $this->operand->propNames());
                foreach ($this->terms as $term) {
                    array_merge($props, $term->propNames());
                }
                return array_unique($props);
            }
        };
    }

    private static function comparison(string $name, FilterTerm $lhs, FilterTerm $rhs, callable $comparer)
    {
        return new class($name, $lhs, $rhs) extends FilterPredicate
        {
            public function __construct(private string $name, private FilterTerm $lhs, private FilterTerm $rhs, private $comparer)
            {
            }
            public function isIdOnly(): bool
            {
                return $this->lhs->isIdOnly() && $this->rhs->isIdOnly();
            }
            public function __invoke(EntryRef $entry)
            {
                return ($this->comparer)(($this->lhs)($entry), ($this->rhs)($entry));
            }
            public function allProps(): array
            {
                $props = [];
                array_merge($props, $this->lhs->propNames());
                array_merge($props, $this->rhs->propNames());
                return array_unique($props);
            }
        };
    }
}

<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filters;

use Antlr\Antlr4\Runtime\Tree\AbstractParseTreeVisitor;
use CannaPress\GcpTables\Filters\Context\AttributeExprContext;
use CannaPress\GcpTables\Filters\Context\BinaryExpressionContext;
use CannaPress\GcpTables\Filters\Context\InExprContext;
use CannaPress\GcpTables\Filters\Context\IsNullExprContext;
use CannaPress\GcpTables\Filters\Context\LikeExprContext;
use CannaPress\GcpTables\Filters\Context\NestedExprContext;
use CannaPress\GcpTables\Filters\Context\NotExprContext;
use CannaPress\GcpTables\Filters\Context\StrLiteralContext;
use CannaPress\GcpTables\Filters\TableFilterVisitor;
use Antlr\Antlr4\Runtime\InputStream;
use CannaPress\GcpTables\Filters\TableFilterLexer;
use CannaPress\GcpTables\Filters\TableFilterParser;

abstract class TableFilter
{
    public abstract function __invoke(TableFilterValueAccessor $entryRef);
    public abstract function __toString(): string;
    protected function __construct(public int $line = 0, public int $column = 0)
    {
    }

    public static function attribute(string $value, int $line, int $column): TableFilter
    {
        return new class($value, $line, $column) extends TableFilter
        {
            public function __construct(private string $value, int $line, int $column)
            {
                parent::__construct($line, $column);
            }
            public  function __toString(): string
            {
                return $this->value;
            }
            public function __invoke(TableFilterValueAccessor $entryRef)
            {
                return $entryRef->get($this->value);
            }
        };
    }
    public static function value(string $value, int $line, int $column): TableFilter
    {
        return new class($value, $line, $column) extends TableFilter
        {
            public function __construct(private string $value, int $line, int $column)
            {
                parent::__construct($line, $column);
            }
            public  function __toString(): string
            {
                return '\'' . str_replace('\'', '\'\'', $this->value) . '\'';
            }
            public function __invoke(TableFilterValueAccessor $entryRef)
            {
                return $this->value;
            }
        };
    }
    public static function not(TableFilter $inner, int $line, int $column): TableFilter
    {
        return new class($inner, $line, $column) extends TableFilter
        {
            public function __construct(private TableFilter $inner, int $line, int $column)
            {
                parent::__construct($line, $column);
            }
            public  function __toString(): string
            {
                return 'not (' . ($this->inner->__toString()) . ')';
            }
            public function __invoke(TableFilterValueAccessor $entryRef)
            {
                return !$this->inner->__invoke($entryRef);
            }
        };
    }

    public static function null(TableFilter $inner, bool $isNotNull, int $line, int $column): TableFilter
    {
        return new class($inner, $isNotNull, $line, $column) extends TableFilter
        {
            public function __construct(private TableFilter $inner, private bool $isNotNull, int $line, int $column)
            {
                parent::__construct($line, $column);
            }
            public  function __toString(): string
            {
                return ($this->inner->__toString()) . ' is '($this->isNotNull ? 'not ' : '') . 'null';
            }
            public function __invoke(TableFilterValueAccessor $entryRef)
            {
                $val = $this->inner->__invoke($entryRef);
                return ($this->isNotNull) ? $val !== null : $val === null;
            }
        };
    }
    public static function in(TableFilter $needle, array $haystack, bool $notIn, int $line, int $column): TableFilter
    {
        return new class($needle, $haystack, $notIn, $line, $column) extends TableFilter
        {
            public function __construct(private TableFilter $needle, private array $haystack, private bool $notIn, int $line, int $column)
            {
                parent::__construct($line, $column);
            }
            public  function __toString(): string
            {
                $result = ($this->needle->__toString()) . ($this->notIn ? ' not in (' : ' in (');
                for ($i = 0; $i < count($this->haystack); $i++) {
                    if ($i > 0) {
                        $result .= ', ';
                    }
                    $result .= $this->haystack[$i]->__toString();
                }
                $result .= ')';

                return $result;
            }
            public function __invoke(TableFilterValueAccessor $entryRef)
            {
                $haystackValues = array_map(fn ($h) => $h->__invoke($entryRef), $this->haystack);
                $needleValue = $this->needle->__invoke($entryRef);
                $res =  in_array($needleValue, $haystackValues);
                return $this->notIn ? !$res : $res;
            }
        };
    }

    public static function like(TableFilter $lhs, TableFilter $rhs, bool $notLike, int $line, int $column)
    {
        return new class($lhs, $rhs, $line, $column) extends TableFilter
        {
            public function __construct(private TableFilter $lhs, private TableFilter $rhs, private bool $notLike, int $line, int $column)
            {
                parent::__construct($line, $column);
            }
            public  function __toString(): string
            {
                return ($this->lhs->__toString()) . ($this->notLike ? ' not like ' : ' like ') . ($this->rhs->__toString());
            }
            public function __invoke(TableFilterValueAccessor $entryRef)
            {
                $rhs = TableFilter::convert_like_to_regex($this->rhs->__invoke($entryRef), $this->line, $this->column);
                $lhs = $this->lhs->__invoke($entryRef);
                $res = preg_match($rhs, $lhs) !== false;
                return $this->notLike ? !$res : $res;
            }
        };
    }

    public static function binary(string $operator, TableFilter $lhs, TableFilter $rhs, int $line, int $column): TableFilter
    {
        //expr ( '<' | '<=' | '>' | '>=' | '==' | '<>' | 'and' | 'or' ) expr #compareExpr
        return new class($operator, $lhs, $rhs, $line, $column) extends TableFilter
        {
            public function __construct(private string $operator, private TableFilter $lhs, private TableFilter $rhs, int $line, int $column)
            {
                parent::__construct($line, $column);
            }
            public  function __toString(): string
            {
                return ($this->lhs->__toString()) . ' ' . $this->operator . ' ' . ($this->rhs->__toString());
            }
            public function __invoke(TableFilterValueAccessor $entryRef)
            {
                $lhsValue = $this->lhs->__invoke($entryRef);
                $rhsValue = $this->rhs->__invoke($entryRef);
                switch ($this->operator) {
                    case '<':
                        return strcmp($lhsValue, $rhsValue) < 0;
                    case '<=':
                        return strcmp($lhsValue, $rhsValue) <= 0;
                    case '>':
                        return strcmp($lhsValue, $rhsValue) > 0;
                    case '>=':
                        return strcmp($lhsValue, $rhsValue) >= 0;
                    case '==':
                        return strcmp($lhsValue, $rhsValue) === 0;
                    case '<>':
                        return strcmp($lhsValue, $rhsValue) !== 0;
                    case 'and':
                        return $lhsValue && $rhsValue;
                    case 'or':
                        return $lhsValue && $rhsValue;
                    default:
                        throw new TableFilterException("Invalid Filter Operator $this->operator", [
                            'line' => $this->line,
                            'column' => $this->column
                        ]);
                }
            }
        };
    }

    private static $compiled_like_cache = [];

    public static function convert_like_to_regex(string $clause, int $line, int $column)
    {
        if (!in_array($clause, TableFilter::$compiled_like_cache)) {
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
                    throw new TableFilterException("Invalid like expression \"$clause\", unexpected sequence at $p", ['clause' => $clause, 'position' => $p, 'line' => $line, 'column' => $column]);
                }
            }
            TableFilter::$compiled_like_cache[$clause] = '/' . $result . '/';
        }
        return TableFilter::$compiled_like_cache[$clause];
    }

    private static function visitor(){
        return new class implements TableFilterVisitor
        {
            private function visitChild($child): TableFilter
            {
                $nextResult = $child->accept($this);
                return $nextResult;
            }
        
            public function visitStrLiteral(StrLiteralContext $context): TableFilter
            {
                $value = $context->STRING_LITERAL()->getText();
                $value = substr($value, 1, strlen($value) - 2);
                $value = str_replace('\'\'', '\'', $value);
                return TableFilter::value($value, $context->start->getLine(), $context->start->getCharPositionInLine());
            }
        
            public function visitNotExpr(NotExprContext $context): TableFilter
            {
                $inner = $this->visitChild($context->expr());
                return TableFilter::not($inner, $context->start->getLine(), $context->start->getCharPositionInLine());
            }
        
            public function visitIsNullExpr(IsNullExprContext $context): TableFilter
            {
                $inner = $this->visitChild($context->expr());
                $line =  $context->start->getLine();
                $column =  $context->start->getCharPositionInLine();
                $isNotNull = $context->K_NOT() !== null;
                return  TableFilter::null($inner, $isNotNull, $line, $column);
            }
        
            public function visitInExpr(InExprContext $context): TableFilter
            {
                $haystack = [];
                $compiled = null;
                for ($i = 0; $i < count($context->children); $i++) {
                    $raw = $context->expr($i);
                    if ($raw != null) {
                        $compiled = $this->visitChild($raw);
                        $haystack[] = $compiled;
                    }
                }
                $needle = array_shift($haystack);
                $line =  $context->start->getLine();
                $column =  $context->start->getCharPositionInLine();
                $notIn = $context->K_NOT() != null;
                return TableFilter::in($needle, $haystack, $notIn, $line, $column);
            }
        
            public function visitNestedExpr(NestedExprContext $context): TableFilter
            {
                return $this->visitChild($context->expr());
            }
        
            public function visitLikeExpr(LikeExprContext $context): TableFilter
            {
                $lhs = $this->visitChild($context->expr(0));
                $rhs = $this->visitChild($context->expr(1));
                $isNotLike = $context->K_NOT() !== null;
                $line =  $context->start->getLine();
                $column =  $context->start->getCharPositionInLine();
                return TableFilter::like($lhs, $rhs, $isNotLike, $line, $column);
            }
        
            public function visitAttributeExpr(AttributeExprContext $context): TableFilter
            {
                $attr = $context->IDENTIFIER()->getText();
                $line =  $context->start->getLine();
                $column =  $context->start->getCharPositionInLine();
                return TableFilter::attribute($attr, $line, $column);
            }
        
            public function visitBinaryExpression(BinaryExpressionContext $context): TableFilter
            {
                $lhs = $this->visitChild($context->expr(0));
                $operator = strtolower(trim($context->OPERATOR()->getText()));
                $rhs = $this->visitChild($context->expr(1));
        
                $line =  $context->start->getLine();
                $column =  $context->start->getCharPositionInLine();
                return TableFilter::binary($operator, $lhs, $rhs,  $line, $column);
            }
        };
        
    }

    public static function parse(string $filterText): ?Filter{
        $filterText = is_null($filterText)? '' :$filterText;        
        $filterText = trim($filterText);
        if(empty($filterText)){
            return null;
        }
        $inputStream = new Antlr\Antlr4\Runtime\InputStream::fromString($filterText);
        $lexer = new TableFilterLexer($inputStream);
        $tokens = new \Antlr\Antlr4\Runtime\CommonTokenStream($lexer);
        $parser = new TableFilterParser($tokens);
        $parser->addErrorListener(new class() extends \Antlr\Antlr4\Runtime\Error\Listeners\BaseErrorListener{
            public function syntaxError(
                Recognizer $recognizer,
                ?object $offendingSymbol,
                int $line,
                int $charPositionInLine,
                string $msg,
                ?RecognitionException $exception,
            ): void {
                throw new TableFilterException($msg, [
                    'line'=>$line,
                    'column'=>$charPositionInLine
                ]);
            }
        });
        $visitor = self::visitor();
        $result = $visitor->visit($parser->expr());
        return $result;
        
    }
}

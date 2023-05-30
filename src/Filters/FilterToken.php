<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filters;


class FilterToken
{
    public const type_value = 'v';
    public const type_operator = 'o';
    public const type_attribute = 'a';
    public const type_whitespace = ' ';
    public const type_lparen = '(';
    public const type_rparen = ')';

    public function __construct(public string $type, public string $value, public int $index)
    {
    }
    public static function tokenize(string $filter)
    {
        $p = 0;
        $filter = trim($filter);
        $length = strlen($filter);

        while ($p < $length) {
            $currentChar = substr($filter, $p, 1);
            if (ctype_space($currentChar) || $currentChar === ',') {
                $i = $p + 1;
                while ($i < $length  && (ctype_space(substr($filter, $i)) || $currentChar === ',')) {
                    $i++;
                }
                yield new FilterToken(self::type_whitespace, substr($filter, $i, $p - $i), $p);
                $p = $i;
            } else if ($currentChar === self::type_lparen || $currentChar === self::type_rparen) {
                yield new FilterToken($currentChar, $currentChar, $p);
                $p++;
            } else if (self::isAtQuotedRun($filter,  $p, '\'', $strVal)) {
                $len = strlen($strVal) + 1;
                $strVal = str_replace('``', '`', $strVal);
                yield new FilterToken(self::type_value, $strVal, $p);
                $p += $len;
            } else if (self::atAttribute($filter, $p, $attrName)) {
                $len = strlen($attrName) + 1;

                yield new FilterToken(self::type_attribute, $attrName, $p);
                $p += $len;
            } else if (self::atOperator($currentChar, $p, $operator)) {
                yield new FilterToken(self::type_operator, $operator, $p);
                $p += strlen($operator);
            } else {
                throw new FilterException("Error parsing \"$filter\" invalid character at $p.", [
                    'filter' => $filter,
                    'postion' => $p
                ]);
            }
        }
    }
    private static function isQuoteChar(string $filter, int $idx, string $quoteChar)
    {
        return substr($filter, $idx, 1) === $quoteChar && ($idx === 0 || !self::isQuoteChar($filter, $idx - 1, $quoteChar));
    }
    private static function isAtQuotedRun(string $filter, int $idx, string $quoteChar, ?string &$value)
    {
        if (substr($filter, $idx, 1) === $quoteChar) {
            $p = $idx + 1;
            $len = strlen($filter);
            while ($p < $len && !self::isQuoteChar($filter, $p, '`')) {
                $p++;
            }
            $value = substr($filter, $idx + 1, $p - ($idx + 1) - 1);
            return true;
        }
        return false;
    }
    public const ALLOWED_ATTRIBUTE_NAME_CHARS = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-.";
    private static function atAttribute(string $filter, int $idx, ?string &$attrName)
    {
        $p = $idx;
        $len = strlen($filter);
        while ($p < $len && strpos(self::ALLOWED_ATTRIBUTE_NAME_CHARS, substr($filter, $p, 1)) !== false) {
            $p++;
        }
        if ($p - $idx > 0) {
            $attrName = substr($filter, $idx, $p - $idx);
            return true;
        }
        $attrName = null;
        return false;
    }

    private static function atOperator($filter, $idx, &$operator)
    {
        foreach (Filter::$operators as $opKeyword => $cnt) {
            if (
                strtolower(substr($filter, $idx, min(strlen($opKeyword), strlen($filter) - $idx))) === $opKeyword
            ) {
                $operator = $opKeyword;
                return true;
            }
        }
        $operator = null;
        return false;
    }
}

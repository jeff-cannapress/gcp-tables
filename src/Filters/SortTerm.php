<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filters;


class SortTerm
{
    public static function parse(string $sortSpec)
    {
        $inner = explode(' ', trim($sortSpec));
        $inner = array_filter($inner, fn ($y) => !empty(trim($y)));
        $direction = 'asc';
        if (isset($inner[1])) {
            $direction = strtolower($inner[1]);
        }
        return new self(TableFilter::attribute($inner[0], 0, 0), $direction);
    }

    public function __construct(private TableFilter $filter, public string $direction)
    {
    }
    public function compare(TableFilterValueAccessor $a, TableFilterValueAccessor $b)
    {
        $aValue = $this->filter->__invoke($a);
        $bValue = $this->filter->__invoke($b);
        return strcmp($aValue, $bValue) * ($this->direction === 'asc' ? 1 : -1);
    }
}

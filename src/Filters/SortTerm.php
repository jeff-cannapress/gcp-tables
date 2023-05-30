<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filters;


class SortTerm
{
    private FilterPredicate $term;
    public function __construct(Filter $filter, public string $direction)
    {
        if ($filter->type !== Filter::type_attribute) {
            throw new FilterException("Unable to compile sort term -- an attribute is required", ['filter' => $filter->serialize()]);
        }
        $this->term = Filter::compile($filter);
    }
    public function compare(FilterValueAccessor $a, FilterValueAccessor $b)
    {
        $aValue = $this->term->__invoke($a);
        $bValue = $this->term->__invoke($b);
        return strcmp($aValue, $bValue) * ($this->direction === 'asc' ? 1 : -1);
    }
}

<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;


class SortTerm
{
    public function __construct(public FilterTerm $term, public string $direction)
    {
    }
}

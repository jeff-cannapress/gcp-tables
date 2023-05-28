<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;


class TabularStorageQueryResult
{
    /**
     * 
     * @param int $totalItems the total number of items matched by the filter
     * @param array $items the item values
     * @return void 
     */
    public function __construct(public int $totalItems, public array $items){}
}

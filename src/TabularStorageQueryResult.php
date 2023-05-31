<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;

use JsonSerializable;

class TabularStorageQueryResult implements JsonSerializable
{
    /**
     * 
     * @param int $totalItems the total number of items matched by the filter
     * @param array $items the item values
     * @return void 
     */
    public function __construct(public int $totalItems, public array $items){}

    public function jsonSerialize(): mixed { 
        return [
            'totalItems'=>$this->totalItems,
            'items'=> array_map(fn($x)=>$x->jsonSerialize(), $this->items),
            'etags'=> array_map(fn($x)=>$x->etag(), $this->items)
        ];
    }
}

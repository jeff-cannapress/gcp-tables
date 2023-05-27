<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;


class StorageListResult
{

    public function __construct(
        public array $items,
        public string $pagingToken,
    )
    {}

}
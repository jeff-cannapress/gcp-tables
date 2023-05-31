<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filters;


interface TableFilterValueAccessor{
    function get(string $key): string|null;
}
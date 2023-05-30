<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filters;

use InvalidArgumentException;

interface FilterPredicate
{
    public function __invoke(FilterValueAccessor $entryRef);
}

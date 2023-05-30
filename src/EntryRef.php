<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;

use CannaPress\GcpTables\Filters\FilterValueAccessor;

interface EntryRef extends FilterValueAccessor
{
    function get(string $attrName): string|null;
    function allAttrs(): array;
    function id(): string;
    function etag(): string;
}

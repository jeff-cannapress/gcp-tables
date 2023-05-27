<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;


interface EntryRef
{
    function get(string $attrName): string|null;
    function allAttrs(): array;
}

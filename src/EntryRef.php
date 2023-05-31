<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;

use CannaPress\GcpTables\Filesystem\StorageItem;
use CannaPress\GcpTables\Filters\TableFilterValueAccessor;
use JsonSerializable;

class EntryRef implements TableFilterValueAccessor, JsonSerializable
{
    private ?array $_allAttrs = null;
    public function __construct(private string $id, private StorageItem $storageItem)
    {
    }

    public function jsonSerialize(): mixed {
        return $this->allAttrs();
    }
    function get(string $attrName): string|null
    {
        if ($attrName === 'id') {
            return $this->id;
        }
        $attrs = $this->allAttrs();
        if (isset($attrs[$attrName])) {
            return $attrs[$attrName];
        }
        return null;
    }
    function allAttrs(): array
    {
        if ($this->_allAttrs === null) {
            $data = $this->storageItem->getData();
            parse_str($data, $this->_allAttrs);
            $this->_allAttrs['id'] = $this->id;
        }
        return $this->_allAttrs;
    }
    function id(): string
    {
        return $this->id;
    }
    function etag(): string
    {
        return $this->storageItem->etag;
    }
}

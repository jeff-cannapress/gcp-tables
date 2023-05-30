<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;

use CannaPress\GcpTables\Filesystem\StorageItem;
use CannaPress\GcpTables\Filesystem\Storage;
use CannaPress\GcpTables\Filters\FilterPredicate;
use CannaPress\GcpTables\Filters\SortTerm;
use InvalidArgumentException;

abstract class TabularStorage
{
    public abstract function domain_create(string $domain): void;
    public abstract function domain_exists(string $domain): bool;
    public abstract function domain_delete(string $domain): void;

    public abstract function entry_put(string $domain, string $id, array $data, ?string $etag): EntryRef;
    public abstract function entry_delete(string $domain, string $id, ?string $etag): void;
    public abstract function entry_get(string $domain, string $id): EntryRef | false;
    public abstract function entry_query(string $domain, FilterPredicate $filter, ?SortTerm $sort, int $skip, int $take, bool $countOnly): TabularStorageQueryResult;


    public static function over_storage(Storage $storage)
    {
        return new class($storage) extends TabularStorage
        {
            public function __construct(private Storage $storage)
            {
            }
            public function domain_create(string $domain): void
            {
                $this->storage->write("/domains/$domain.exists", "1", null);
            }
            public function domain_exists(string $domain): bool
            {
                return $this->storage->exists("/domains/$domain.exists");
            }
            public function domain_delete(string $domain): void
            {
                if ($this->storage->exists("/domains/$domain.exists")) {
                    $this->storage->delete("/domains/$domain.exists");
                    $countResults = 0;
                    do {
                        $res = $this->storage->list(prefix: "/domains/$domain/");
                        $countResults = count($res->items);
                        foreach ($res->items as $path) {
                            $this->storage->delete($path);
                        }
                    } while ($countResults > 0);
                }
            }
            public function entry_put(string $domain, string $id, array $data, ?string $etag): EntryRef
            {
                if (!$this->domain_exists($domain)) {
                    throw new InvalidArgumentException("Domain does not exist $domain");
                }
                $path = "/domains/$domain/$id";
                $updated = $this->storage->write($path, http_build_query($data), $etag);
                return self::entry($id, $updated);
            }
            public function entry_get(string $domain, string $id): EntryRef | false
            {
                if (!$this->domain_exists($domain)) {
                    throw new InvalidArgumentException("Domain does not exist $domain");
                }
                $path = "/domains/$domain/$id";
                if (!$this->storage->exists($path)) {
                    return false;
                }
                return self::entry($id, $this->storage->read($path));
            }
            public function entry_delete(string $domain, string $id, ?string $etag): void
            {
                if (!$this->domain_exists($domain)) {
                    throw new InvalidArgumentException("Domain does not exist $domain");
                }
                $this->storage->delete("/domains/$domain/$id");
            }
            public function entry_query(string $domain, FilterPredicate $filter, ?SortTerm $sort, int $skip, int $take, bool $countOnly): TabularStorageQueryResult
            {
                if (!$this->domain_exists($domain)) {
                    throw new InvalidArgumentException("Domain does not exist $domain");
                }
                $entryRefs = [];
                $pagingToken = '';
                do {
                    $res = $this->storage->list("/domains/$domain/");
                    foreach ($res->items as $item) {
                        $id = substr($item->path, strlen("/domains/$domain/"));
                        $entry = self::entry($id, $item);
                        if ($filter->__invoke($entry)) {
                            $entryRefs[] = $entry;
                        }
                    }
                    $pagingToken = $res->pagingToken;
                } while (!empty($pagingToken));
                if ($countOnly) {
                    return new TabularStorageQueryResult(count($entryRefs), []);
                }
                if (!is_null($sort)) {
                    uasort($entryRefs, fn ($a, $b) => $sort->compare($a, $b));
                }
                if ($take === -1) {
                    $take = count($entryRefs) - $skip;
                }
                $entryRefs = array_slice($entryRefs, $skip, $take);
                return new TabularStorageQueryResult(
                    count(array_keys($entryRefs)),
                    $entryRefs
                );
            }

            private static function entry(string $id, StorageItem $storageItem): EntryRef
            {
                return new class($id, $storageItem) implements EntryRef
                {
                    private ?array $_allAttrs = null;
                    public function __construct(private string $id, private StorageItem $storageItem)
                    {
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
                };
            }
        };
    }
}

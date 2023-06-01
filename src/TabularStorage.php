<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;

use CannaPress\GcpTables\Filesystem\Storage;
use CannaPress\GcpTables\Filters\TableFilter;
use CannaPress\GcpTables\Filters\SortTerm;
use InvalidArgumentException;

abstract class TabularStorage
{
    public abstract function cleanup_deleted_domain(string $domain, string $workId): void;
    public abstract function domain_create(string $domain): void;
    public abstract function domain_exists(string $domain): string|false;
    public abstract function domain_delete(string $domain): string;
    public abstract function domain_list(): array;

    public abstract function entry_put(string $domain, string $id, array $data, ?string $etag): EntryRef;
    public abstract function entry_delete(string $domain, string $id, ?string $etag): void;
    public abstract function entry_get(string $domain, string $id): EntryRef | false;
    public abstract function entry_query(string $domain, ?TableFilter $filter, ?SortTerm $sort, int $skip, int $take, bool $countOnly): TabularStorageQueryResult;


    public static function over_storage(Storage $storage)
    {
        return new class($storage) extends TabularStorage
        {
            public function __construct(private Storage $storage)
            {
            }
            public function domain_create(string $domain): void
            {
                $this->storage->write("/domains/$domain.exists", strval(microtime(true)), null);
            }
            public function domain_exists(string $domain): string|false
            {
                $key = $this->storage->read("/domains/$domain.exists");
                if ($key !== null) {
                    return $key->etag;
                }
                return false;
            }
            public function domain_delete(string $domain): string
            {
                if ($this->storage->read("/domains/$domain.exists")) {
                    $this->storage->delete("/domains/$domain.exists");
                    $workId = strval(microtime(true));
                    $this->storage->write("/domains/$domain.deleted", $workId, null);
                    return $workId;
                }
                return '';
            }
            public function cleanup_deleted_domain(string $domain, string $workId): void
            {

                $deletedRef = $this->storage->read("/domains/$domain.deleted");
                if ($deletedRef && $deletedRef->getData() === $workId) {
                    $this->storage->delete("/domains/$domain.deleted");
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
            public function domain_list(): array
            {
                $pagingToken = '';
                $result = [];
                do {
                    $res = $this->storage->list(prefix: "/domains/", delimiter: '/');
                    foreach ($res->items as $path) {
                        if (!str_ends_with($path, '.deleted')) {
                            $name = substr($path, 9/*strlen('/domains/')*/, strlen($path) - 16/*strlen('/domains/')+strlen('.exists') */);
                            $result[$name] = $this->storage->read($path)->etag;
                        }
                    }
                    $pagingToken = $res->pagingToken;
                } while (!empty($pagingToken));
                return $result;
            }

            public function entry_put(string $domain, string $id, array $data, ?string $etag): EntryRef
            {
                if (!$this->domain_exists($domain)) {
                    throw new InvalidArgumentException("Domain does not exist $domain");
                }
                $path = "/domains/$domain/$id";
                $updated = $this->storage->write($path, http_build_query($data), $etag);
                $this->storage->write("/domains/$domain.exists", strval(microtime(true)), null);
                return new EntryRef($id, $updated);
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
                return new EntryRef($id, $this->storage->read($path));
            }
            public function entry_delete(string $domain, string $id, ?string $etag): void
            {
                if (!$this->domain_exists($domain)) {
                    throw new InvalidArgumentException("Domain does not exist $domain");
                }
                $this->storage->delete("/domains/$domain/$id");
                $this->storage->write("/domains/$domain.exists", strval(microtime(true)), null);
            }
            public function entry_query(string $domain, ?TableFilter $filter, ?SortTerm $sort, int $skip, int $take, bool $countOnly): TabularStorageQueryResult
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
                        $entry = new EntryRef($id, $item);
                        if (is_null($filter) || $filter->__invoke($entry)) {
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
        };
    }
}

<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;

use CannaPress\GcpTables\FilterPredicate;

use Exception;
use InvalidArgumentException;

abstract class TabularStorage
{
    public abstract function domain_create(string $domain): void;
    public abstract function domain_exists(string $domain): bool;
    public abstract function domain_delete(string $domain): void;

    public abstract function item_put(string $domain, string $id, array $data): void;
    public abstract function item_get(string $domain, string $id, array $data): array|false;
    public abstract function item_remove(string $domain, string $id): void;
    public abstract function item_query(string $domain, FilterPredicate $filter, array $sort, int $skip = 0, int $take = -1): array;


    public static function over_storage(Storage $storage)
    {
        return new class($storage) extends TabularStorage
        {
            public function __construct(private Storage $storage)
            {
            }
            public function domain_create(string $domain): void
            {
                $this->storage->write("/domains/$domain.exists", "1");
            }
            public function domain_exists(string $domain): bool
            {
                return $this->storage->exists("/domains/$domain.exists");
            }
            public function domain_delete(string $domain): void
            {
                if ($this->storage->exists("/domains/$domain.exists")) {
                    $countResults = 0;
                    do {
                        $res = $this->storage->list(prefix: "/domains/$domain/");
                        $countResults = count($res->items);
                        foreach ($res->items as $path) {
                            $this->storage->delete($path);
                        }
                    } while ($countResults > 0);
                    $all = $this->storage->list("/domains/$domain/");
                    foreach ($all as $file) {
                        $this->storage->delete($file);
                    }
                }
            }
            public function item_put(string $domain, string $id, array $data): void
            {
                if (!$this->domain_exists($domain)) {
                    throw new InvalidArgumentException("Domain does not exist $domain");
                }
                $serialized = json_encode($data);
                $this->storage->write("/domains/$domain/$id.json", $serialized);
            }
            public function item_get(string $domain, string $id, array $data): array|false
            {
                if (!$this->domain_exists($domain)) {
                    throw new InvalidArgumentException("Domain does not exist $domain");
                }
                $path = "/domains/$domain/$id.json";
                if (!$this->storage->exists($path)) {
                    return false;
                }
                $data = $this->storage->read($path);
                return json_decode($data->getContents(), true);
            }
            public function item_remove(string $domain, string $id): void
            {
                if (!$this->domain_exists($domain)) {
                    throw new InvalidArgumentException("Domain does not exist $domain");
                }
                $this->storage->delete("/domains/$domain/$id.json");
            }
            public function item_query(string $domain, FilterPredicate $filter, array $sort, int $skip = 0, int $take = -1): array
            {
                if (!$this->domain_exists($domain)) {
                    throw new InvalidArgumentException("Domain does not exist $domain");
                }
                $predicate = FilterPredicate::parse($filter);

            }
            private static function entry($id, $path){
                
            }
        };
    }
}

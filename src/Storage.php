<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;

use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

abstract class Storage
{

    public abstract function write(string $path, string $data, ?string $etag): StorageItem;
    public abstract function exists(string $path): bool;
    public abstract function read(string $path): StorageItem;
    public abstract function list(string $prefix = '', string $delimiter = '', int $pageSize = 1000, string $pagingToken = ''): StorageListResult;
    public abstract function delete(string $path): void;


    public static function in_memory(): Storage
    {
        return new class() extends Storage
        {
            private array $objects = [];

            public function delete(string $path): void
            {
                if (isset($this->objects[$path])) {
                    unset($this->objects[$path]);
                }
            }

            public function write(string $path, string $data, ?string $etag): StorageItem
            {
                if (!is_null($etag)) {
                    if (!isset($this->objects[$path])) {
                        throw new InvalidArgumentException("An etag was supplied but no object at that path was found");
                    } else if ($this->objects[$path]['etag'] !== $etag) {
                        throw new InvalidArgumentException("An etag was supplied but no object at that path was found");
                    }
                }
                $this->objects[$path] = [
                    'data' =>  $data,
                    'etag' => (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DateTimeImmutable::ATOM)
                ];
                return new StorageItem($path, $this->objects[$path]['etag'], fn () => $this->objects[$path]['data']);
            }
            public function exists(string $path): bool
            {
                return isset($this->objects[$path]);
            }
            public function read(string $path): StorageItem
            {
                if (!$this->exists($path)) {
                    throw new \RuntimeException("$path doesnt exist");
                }
                return new StorageItem($path, $this->objects[$path]['etag'], fn () => $this->objects[$path]['data']);
            }

            public function list(string $prefix = '', string $delimiter = '', int $pageSize = 1000, string $pagingToken = ''): StorageListResult
            {
                $all = array_keys($this->objects);
                $offset = !empty($pagingToken) ? intval($pagingToken) : 0;
                if ($offset < 0) {
                    $offset = 0;
                }
                if (!empty($prefix)) {
                    $all = array_filter($all, fn ($path) => str_starts_with($path, $prefix));
                }
                if (!empty($delimiter)) {
                    $all = array_filter($all, fn ($path) => strpos($path, $delimiter, strlen($prefix) + strlen(($delimiter))) === false);
                }
                $length = min($pageSize, count($all) - $offset);
                $slice = array_slice($all, $offset, $length);
                $resultPageToken = '';
                if ($offset + $length < count($all)) {
                    $resultPageToken = strval($offset + $length);
                }
                $storage_items = array_map(function ($path) {
                    new StorageItem($path, $this->objects[$path]['etag'], fn () => $this->objects[$path]['data']);
                }, $slice);
                return new StorageListResult($storage_items, $resultPageToken);
            }
        };
    }
}

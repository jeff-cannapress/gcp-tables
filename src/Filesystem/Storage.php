<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filesystem;

use DateTimeImmutable;
use DateTimeZone;
use Google\Cloud\Storage\Bucket;
use InvalidArgumentException;

abstract class Storage
{

    public abstract function write(string $path, string $data, ?string $etag = null): StorageItem;
    public abstract function read(string $path): ?StorageItem;
    public abstract function list(string $prefix = '', string $delimiter = '', int $pageSize = 1000, string $pagingToken = ''): StorageListResult;
    public abstract function delete(string $path, ?string $etag = null): void;


    public static function in_memory(): Storage
    {
        return new class() extends Storage
        {
            private array $objects = [];

            public function delete(string $path, ?string $etag = null): void
            {
                if (!is_null($etag)) {
                    if (!isset($this->objects[$path])) {
                        throw new StorageException("An etag was supplied but no object at that path was found", ['path' => $path, 'etag' => $etag]);
                    } else if ($this->objects[$path]['etag'] !== $etag) {
                        throw new StorageException("An etag was supplied but no object at that path was found", ['path' => $path, 'etag' => $etag]);
                    }
                }
                if (isset($this->objects[$path])) {
                    unset($this->objects[$path]);
                }
            }

            public function write(string $path, string $data, ?string $etag = null): StorageItem
            {
                if (!is_null($etag)) {
                    if (!isset($this->objects[$path])) {
                        throw new StorageException("An etag was supplied but no object at that path was found", ['path' => $path, 'etag' => $etag]);
                    } else if ($this->objects[$path]['etag'] !== $etag) {
                        throw new StorageException("An etag was supplied but no object at that path was found", ['path' => $path, 'etag' => $etag]);
                    }
                }
                $this->objects[$path] = [
                    'data' =>  $data,
                    'etag' => (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DateTimeImmutable::ATOM)
                ];
                return new StorageItem($path, $this->objects[$path]['etag'], fn () => $this->objects[$path]['data']);
            }

            public function read(string $path): ?StorageItem
            {
                if (!isset($this->objects[$path])) {
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

    public static function in_cloud(Bucket $bucket){
        return new class($bucket) extends Storage
        {
            public function __construct(private Bucket $bucket)
            {
                
            }

            public function delete(string $path, ?string $etag = null): void
            {
                $obj = $this->bucket->object($path);
                try{

                    if(!is_null($etag)){
                        $params['ifGenerationMatch'] = $etag;
                    }
                    $obj->delete($params)
                }
                catch(\Throwable $ex){
                    throw new StorageException('error deleting path',['path' => $path, 'etag' => $etag]);
                }

            }

            public function write(string $path, string $data, ?string $etag = null): StorageItem
            {
                if (!is_null($etag)) {
                    if (!isset($this->objects[$path])) {
                        throw new StorageException("An etag was supplied but no object at that path was found", ['path' => $path, 'etag' => $etag]);
                    } else if ($this->objects[$path]['etag'] !== $etag) {
                        throw new StorageException("An etag was supplied but no object at that path was found", ['path' => $path, 'etag' => $etag]);
                    }
                }
                $this->objects[$path] = [
                    'data' =>  $data,
                    'etag' => (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DateTimeImmutable::ATOM)
                ];
                return new StorageItem($path, $this->objects[$path]['etag'], fn () => $this->objects[$path]['data']);
            }

            public function read(string $path): ?StorageItem
            {
                if (!isset($this->objects[$path])) {
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

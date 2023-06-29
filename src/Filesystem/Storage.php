<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filesystem;

use DateTimeImmutable;
use DateTimeZone;
use Google\Cloud\Storage\Bucket;
use Google\Cloud\Storage\StorageClient;

use React\Promise\PromiseInterface;

abstract class Storage
{

    /** @return PromiseInterface<StorageItem> */
    public abstract function write(string $path, string $data, ?string $etag = null): PromiseInterface;
    public abstract function read(string $path): ?StorageItem;
    public abstract function list(string $prefix = '', string $delimiter = '', int $pageSize = 1000, string $pagingToken = ''): StorageListResult;
    public abstract function delete(string $path, ?string $etag = null): void;


    // public static function on_disk($rootPath) : Storage
    // {
    //     $rootPath = ltrim($rootPath, '/');
    //     return new class() extends Storage
    //     {
    //         public abstract function write(string $path, string $data, ?string $etag = null): StorageItem;
    //         public abstract function read(string $path): ?StorageItem;
    //         public abstract function list(string $prefix = '', string $delimiter = '', int $pageSize = 1000, string $pagingToken = ''): StorageListResult;
    //         public abstract function delete(string $path, ?string $etag = null): void;
    //     };
    // }
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

    public static function from_google(StorageClient $storage, string $bucketName){

        return new class($storage, $bucketName) extends Storage{
            private Bucket $bucket;
            public function __construct(StorageClient $google, string $bucketName)
            {
                $this->bucket = $google->bucket($bucketName);
            }

            public function delete(string $path, ?string $etag = null): void
            {
                $obj = $this->bucket->object($path);
                if(is_null($obj) || !$obj->exists()){
                    return;
                }
                $info = $obj->info();
                $existingEtag = isset($info['etag'])? $info['etag'] : null;
                if(!is_null($existingEtag) && $existingEtag !== $etag){
                    throw new StorageException("An etag was supplied but did not match ", ['path' => $path, 'etag' => $etag, 'existingEtag'=>$existingEtag ]);
                }
                $obj->delete();
            }

            public function write(string $path, string $data, ?string $etag = null): StorageItem
            {
                $obj = $this->bucket->object($path);
                $existingEtag= null;
                if(!is_null($obj)){
                    $info = $obj->info();
                    $existingEtag = isset($info['etag'])? $info['etag'] : null;
                }
                if(!is_null($existingEtag) && $existingEtag !== $etag){
                    throw new StorageException("An etag was supplied but did not match ", ['path' => $path, 'etag' => $etag, 'existingEtag'=>$existingEtag ]);
                }
                $newEtag = strval(microtime(true));
                $uploaded = $this->bucket->upload($data, [
                    'name'=>$path,
                    'etag'=>$newEtag
                ]);
            
                return new StorageItem($path, $newEtag, fn()=> $uploaded->downloadAsString());
            }

            public function read(string $path): ?StorageItem
            {
                $obj = $this->bucket->object($path);
                if(is_null($obj) || ! $obj->exists()){
                    return null;
                }
                $info = $obj->info()['etag'];
                return new StorageItem($path, $info['etag'], fn () => $obj->downloadAsString());
            }

            public function list(string $prefix = '', string $delimiter = '', int $pageSize = 1000, string $pagingToken = ''): StorageListResult
            {
                $page = $this->bucket->objects([
                    'delimiter'=>$delimiter,
                    'prefix'=>$prefix,
                    'pageToken' => $pagingToken,
                    'maxResults'=>$pageSize
                ]);
                foreach($page as $idx => $obj){
                    
                }

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
        }
    }

}

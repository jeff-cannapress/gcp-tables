<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filesystem;
use React\Promise\PromiseInterface;

interface StorageItem{
    public string $path;
    public string $etag;
    public function read():PromiseInterface;
    public function write():PromiseInterface;
}
// class StorageItem
// {
//     private ?string $data = null;
//     public function __construct(public string $path, public string $etag, private $getter)
//     {
//     }
//     public function getData(): string
//     {
//         if ($this->data === null) {
//             $this->data = ($this->getter)();
//         }
//         return $this->data;
//     }
// }

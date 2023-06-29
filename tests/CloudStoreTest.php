<?php

namespace CannaPress\GcpTables\Tests;


use Google\Cloud\Storage\StorageClient;

use PHPUnit\Framework\TestCase;



class CloudStoreTest extends TestCase
{


    public function testCanParseAndReserialize()
    {
        $storage = new StorageClient();

        $bucketId = 'cp-tabular-bucket';
        $gcsBucket = $storage->bucket($bucketId);

        $obj = $gcsBucket->upload('abcdef', [
            'name' => 'hello.txt',
            'etag'=>strval(microtime(true))
        ]);
        // $firstGen = $obj->identity()['generation'];
        // $obj2 = $gcsBucket->upload('abcdef', [
        //     'name' => 'hello.txt',
        // ]);
        $info = $gcsBucket->object('hello.txt');
        $this->assertFalse(false);
    }
}

<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filesystem;

use Exception;


class StorageException extends Exception
{

    public function __construct(public string $message, public array $errorDetail)
    {
        parent::__construct($message);
    }

}

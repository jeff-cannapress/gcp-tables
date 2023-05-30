<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Filters;

use Exception;


class FilterException extends Exception
{

    public function __construct(public string $message, public array $errorDetail)
    {
        parent::__construct($message);
    }

}

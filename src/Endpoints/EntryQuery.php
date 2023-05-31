<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Endpoints;

use CannaPress\GcpTables\TabularStorage;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class EntryQuery implements Endpoint
{   
    public function invoke(RequestInterface $request, EndpointParameters $params): ResponseInterface{
        throw new \Exception("Not Implemented");
    }
}
<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Endpoints;

use CannaPress\GcpTables\TabularStorage;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class DomainList implements Endpoint
{   
    public function __construct(private TabularStorage $tabularStorage)
    {
        
    }
    public function invoke(RequestInterface $request, EndpointParameters $params): ResponseInterface{
        throw new \Exception("Not Implemented");
    }
}
<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Endpoints;

use CannaPress\GcpTables\Filters\SortTerm;
use CannaPress\GcpTables\Filters\TableFilter;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use \GuzzleHttp\Psr7\Response;

interface Endpoint
{

    function invoke(RequestInterface $request, EndpointParameters $params): ResponseInterface;

}

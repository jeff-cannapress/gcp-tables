<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Endpoints;

use CannaPress\GcpTables\TabularStorage;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Router implements Endpoint
{    

    public function __construct(private TabularStorage $tabularStorage)
    {
        
    }
    public function invoke(RequestInterface $request, EndpointParameters $params): ResponseInterface{

        try {
            $endpoint = $this->getEndpoint($request, $params);
            if($endpoint === null){
                return new Response(404, ['Content-Type'=>'text/plain'], 'Endpoint not found');
            }
            else{
                return $endpoint->invoke($request, $params);
            }

        } catch (\Throwable $ex) {
            $includeTrace = true;
            if ($ex instanceof ApiException) {
                return $ex->getResponse($includeTrace);
            } else {
                $body = array_merge([
                    'message' => $ex->getMessage(),
                ], ($includeTrace ? [
                    'trace' => $ex->getTraceAsString()
                ] : []));
                return new Response(500, ['Content-Type' => 'application/x-www-form-urlencoded'], http_build_query($body));
            }
        }


    }
    public function getEndpoint(RequestInterface $request, EndpointParameters $params) :?Endpoint{
        $is_entry = !is_null($params->entryId) || !empty($params->hasQuery);

        if($is_entry){
            if($params->method === 'get' && !is_null($params->entryId)){
                return new EntryGet($this->tabularStorage);
            }
            else if ($params->method=== 'put'){
                return new EntryPut($this->tabularStorage);
            }
            else if($params->method === 'delete'){
                return new EntryDelete($this->tabularStorage);
            }
            else if($params->hasQuery){
                return new EntryQuery($this->tabularStorage);
            }
            else{
                return null;
            }
        }
        else{
            if($params->method === 'get'){
                if(!is_null($params->domainId)){
                    return new DomainExists($this->tabularStorage);
                }
                return new DomainList($this->tabularStorage);
            }
            else{
                if(is_null($params->domainId)){
                    return null;
                }
                else if($params->method === 'put'){
                    return new DomainCreate($this->tabularStorage);
                }
                else if($params->method === 'delete'){
                    if($params->hasQuery && !is_null($params->cleanup)){
                        return new DomainCleanup($this->tabularStorage);
                    }
                    return new DomainDelete($this->tabularStorage);
                }
            } 
        }
        throw new \Exception("not implemented");
    }
    
}
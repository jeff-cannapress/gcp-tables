<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;

use CannaPress\GcpTables\Filters\SortTerm;
use CannaPress\GcpTables\Filters\TableFilter;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use \GuzzleHttp\Psr7\Response;
use CannaPress\GcpTables\Endpoints\ApiException;

class Endpoint
{
    public const FORM_ENCODED = 'application/x-www-form-urlencoded';
    public function __construct(private TabularStorage $tables, private string $pathPrefix)
    {
    }
    public static function parsePath($path): array
    {
        $parts = explode('/', trim($path, '/'), 2);

        if (count($parts) == 1) {
            $parts[1] = '';
        }
        return $parts;
    }

    private static function build_entry_response(EntryRef $entry)
    {
        return  ['id' => $entry->id(), 'etag' => $entry->etag(), 'attrs' => $entry->allAttrs()];
    }
    public function invokeRequest(RequestInterface $request): ResponseInterface
    {
        $path = trim(substr(trim($request->getUri()->getPath(), '/'), strlen($this->pathPrefix)), '/');
        $path = explode('/', $path, 2);
        $path[1] = isset($path[1]) ? $path[1] : null;
        list($domain, $id) = $path;
        if(empty($domain)){
            throw new ApiException(404, 'Invalid Endpoint -- no domain found', ['uri'=> $request->getUri()->__toString()]);
        }
        $query = $request->getUri()->getQuery();
        $is_entry = !is_null($id) || !empty($query);
        if($is_entry){

        }

        if (!empty($query)) {
            parse_str($query, $query);
        } else {
            $query = null;
        }
        $etag = $request->getHeader('if-match');
        $etag = empty($etag) ? null : $etag[0];
        $method = strtoupper($request->getMethod());
        $contentType = strtolower($request->getHeader('Content-Type')[0]);
        $bodyContents = $request->getBody()->getContents();
        return $this->executeRequest($method, $domain, $id, $query, $etag, $contentType, $bodyContents);
    }
    public function executeRequest(string $method, string $domain, ?string $id, ?array $query, ?string $etag, string $contentType, string $bodyContents): ResponseInterface
    {
        $is_entry = !is_null($id) || !empty($query);
        if ($is_entry) {
            if ($method === 'GET') {
                if (!empty($id)) {
                    $entry = $this->tables->entry_get($domain, $id);

                    return new Response(200, [
                        'headers' => [
                            'ETag' => $entry->etag(),
                            'Content-Type' => self::FORM_ENCODED,
                        ],
                    ], http_build_query(self::build_entry_response($entry)));
                } else {
                    try {
                        if (!isset($query['filter']) || empty($query['filter'])) {
                            return new Response(422, [], "Either a filter query parameter or id path parameter is required");
                        }
                        $filter = TableFilter::parse($query['filter']);
                        $sort = isset($query['sort']) ? SortTerm::parse($query['sort']) : null;
                        $skip = isset($query['skip']) ? intval($query['skip']) : 0;
                        $take = isset($query['take']) ? intval($query['take']) : -1;
                        $countOnly = isset($query['countOnly']) && ($query['countOnly'] === '1' || $query['countOnly'] === 'true');
                        $queryResult = $this->tables->entry_query($domain, $filter, $sort, $skip, $take, $countOnly);
                        $responseBody = [
                            'totalItems' => $queryResult->totalItems,
                            'items' => array_map(fn ($entry) => self::build_entry_response($entry), $queryResult->items),

                        ];
                        return new Response(200, [
                            'headers' => [
                                'Content-Type' => self::FORM_ENCODED,
                            ],
                        ], http_build_query($responseBody));
                    } catch (\Throwable $ex) {
                        if($ex instanceof \CannaPress\GcpTables\Filesystem\StorageException){

                        }
                        else if($ex instanceof \CannaPress\GcpTables\Filters\TableFilterException){

                        }
                    }
                }

            } else if ($method  === 'PUT') {

                if ($contentType !== self::FORM_ENCODED) {
                    return new Response(415, [], "Invalid Content-Type \"$contentType\" PUT requires application/x-www-form-urlencoded");
                }

                parse_str($bodyContents, $data);
                $result = $this->tables->entry_put($domain, $id, $data, $etag);
                return new Response(200, [
                    'headers' => [
                        'ETag' => $result->etag()
                    ]
                ], "OK");
            } else if ($method  === 'DELETE') {
                if (!$this->tables->entry_get($domain, $id)) {
                    return new Response(404, [], "\"/$domain/$id\" not found");
                }
                $this->tables->entry_delete($domain, $id, $etag);
                return new Response(200, [], "OK");
            } else {
                return new Response(405, [], "Invalid method \"$method\", allowed methods are GET, PUT, DELETE");
            }
        } else {
            if ($method === 'HEAD') {
            } else if ($method  === 'PUT') {
            } else if ($method  === 'DELETE') {
            } else {
                return new Response(405, [], "Invalid method \"$method\", allowed methods are HEAD, PUT, DELETE");
            }
        }
    }
}

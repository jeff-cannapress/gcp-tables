<?php

declare(strict_types=1);

namespace CannaPress\GcpTables\Endpoints;

use Psr\Http\Message\RequestInterface;


class EndpointParameters
{
    public ?string $domainId = null;
    public ?string $entryId = null;
    public ?string $ifMatch = null;
    public ?string $filter = null;
    public ?string $sort = null;
    public int $skip = 0;
    public int $take = -1;
    public bool $countOnly = false;
    public bool $hasQuery = false;
    public ?string $method = null;
    public ?string $cleanup = null;
    public function __construct(RequestInterface|array $request)
    {
        if (!is_array($request)) {

            $path_prefix = '/' . getenv('FUNCTION_TARGET');
            $path = $request->getUri()->getPath();
            if (strlen($path_prefix) >= strlen($path)) {
                $path = trim(substr($path, strlen($path_prefix)), '/');
            } else {
                $path = '';
            }
            $path = explode('/', $path, 2);
            $ifMatchHeader = $request->getHeader('If-Match');
            $this->domainId = isset($path[0]) && !empty($path[0]) ? $path[0] : null;
            $this->entryId = isset($path[1]) && !empty($path[1]) ? $path[1] : null;
            $this->ifMatch = isset($ifMatchHeader[0]) && !empty($ifMatchHeader[0]) ? $ifMatchHeader[0] : null;
            $query = $request->getUri()->getQuery();
            if (!is_null($query) && !empty($query)) {
                $this->hasQuery = false;
                $query = [];
            } else {
                $this->hasQuery = true;
                parse_str($query, $query);
            }
            $this->filter = isset($query['filter']) && !empty($query['filter']) ? $query['filter'] : null;
            $this->sort = isset($query['sort']) && !empty($query['sort']) ? $query['sort'] : null;
            $this->skip = isset($query['skip']) && !empty($query['skip']) ? intval($query['skip']) : 0;
            $this->take = isset($query['take']) && !empty($query['take']) ? intval($query['take']) : 0;
            $this->countOnly = isset($query['countOnly']) && !empty($query['countOnly']) ? (strtolower($query['countOnly']) === 'true' || $query['countOnly'] === '1')  : false;
            $this->cleanup = isset($query['cleanup']) && !empty($query['cleanup']) ? $query['cleanup'] : null;

            $this->method = strtolower($request->getMethod());
        } else {
            foreach ($request as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }
}

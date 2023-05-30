<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class ApiException extends Exception
{

    public function __construct(public int $status, public string $message, public array $errorDetail)
    {
        parent::__construct($message, $status);
    }
    public function getResponse(bool $includeTrace = false): ResponseInterface
    {
        $body = array_merge([
            'message' => $this->message,
            'errorDetail' => $this->errorDetail,
        ], ($includeTrace ? [
            'trace' => $this->getTraceAsString()
        ] : []));
        return new Response($this->status, ['Content-Type' => 'application/x-www-form-urlencoded'], http_build_query($body));
    }
}

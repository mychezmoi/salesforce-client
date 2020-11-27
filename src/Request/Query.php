<?php

namespace Mcm\SalesforceClient\Request;

use Symfony\Component\HttpFoundation\Request;

class Query implements RequestInterface
{
    const ENDPOINT = 'query/';

    private string $queryString;

    public function __construct(string $queryString)
    {
        $this->queryString = $queryString;
    }

    public function getEndpoint(): string
    {
        return sprintf(
            '%s?%s',
            self::ENDPOINT,
            http_build_query(['q' => $this->queryString], null, '&')
        );
    }

    public function getMethod(): string
    {
        return Request::METHOD_GET;
    }

    public function getParams(): array
    {
        return [];
    }

    public function hasBody(): bool
    {
        return false;
    }
}

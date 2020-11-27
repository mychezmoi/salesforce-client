<?php

namespace Mcm\SalesforceClient\Request;

use Symfony\Component\HttpFoundation\Request;

class QueryNext implements RequestInterface
{
    const ENDPOINT = 'query/%s';

    private string $nextResultIdentifier;

    public function __construct(string $nextResultIdentifier)
    {
        $this->nextResultIdentifier = $nextResultIdentifier;
    }

    public function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT, $this->nextResultIdentifier);
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

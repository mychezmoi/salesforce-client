<?php

namespace Mcm\SalesforceClient\Request;

use Symfony\Component\HttpFoundation\Request;

class Delete implements RequestInterface
{
    const ENDPOINT = 'sobjects/%s/%s/';

    protected string $objectType;

    protected string $id;

    public function __construct(string $objectType, string $id)
    {
        $this->objectType = $objectType;
        $this->id         = $id;
    }


    public function getMethod(): string
    {
        return Request::METHOD_DELETE;
    }


    public function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT, $this->objectType, $this->id);
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

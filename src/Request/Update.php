<?php

namespace Mcm\SalesforceClient\Request;

use Symfony\Component\HttpFoundation\Request;

class Update implements RequestInterface
{
    const ENDPOINT = 'sobjects/%s/%s/';

    protected string $objectType;

    protected string $id;

    protected array $params;

    public function __construct(string $objectType, string $id, array $params = [])
    {
        $this->objectType = $objectType;
        $this->id         = $id;
        $this->params     = $params;
    }


    public function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT, $this->objectType, $this->id);
    }


    public function getMethod(): string
    {
        return Request::METHOD_PATCH;
    }


    public function getParams(): array
    {
        return $this->params;
    }


    public function hasBody(): bool
    {
        return true;
    }
}

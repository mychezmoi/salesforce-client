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

    /**
     * {@inheritdoc}
     */
    public function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT, $this->objectType, $this->id);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): string
    {
        return Request::METHOD_PATCH;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     */
    public function hasBody(): bool
    {
        return true;
    }
}

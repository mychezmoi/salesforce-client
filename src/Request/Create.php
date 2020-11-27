<?php

namespace Mcm\SalesforceClient\Request;

use Symfony\Component\HttpFoundation\Request;

class Create implements RequestInterface
{
    const ENDPOINT = 'sobjects/%s/';

    protected string $objectType;

    protected array $params;

    public function __construct(string $objectType, array $params = [])
    {
        $this->objectType = $objectType;
        $this->params     = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT, $this->objectType);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): string
    {
        return Request::METHOD_POST;
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

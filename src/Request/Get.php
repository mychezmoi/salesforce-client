<?php

namespace Mcm\SalesforceClient\Request;

use Symfony\Component\HttpFoundation\Request;

class Get implements RequestInterface
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
        $endPoint = sprintf(self::ENDPOINT, $this->objectType, $this->id);

        if (!empty($this->getParams())) {
            $endPoint .= '?'.http_build_query(['fields' => implode(',', $this->params)]);
        }

        return $endPoint;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): string
    {
        return Request::METHOD_GET;
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
        return false;
    }
}

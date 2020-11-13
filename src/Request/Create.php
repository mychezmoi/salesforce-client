<?php

namespace Mcm\SalesforceClient\Request;

use Mcm\SalesforceClient\Enum\ContentType;
use Mcm\SalesforceClient\Enum\RequestMethod;

class Create implements RequestInterface
{
    const ENDPOINT = '/sobjects/%s/';

    protected string $objectType;

    protected array $params;

    public function __construct(string $objectType, array $params = [])
    {
        $this->objectType = $objectType;
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT, $this->objectType->value());
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): RequestMethod
    {
        return RequestMethod::POST();
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
    public function getContentType(): string
    {
        return ContentType::JSON;
    }
}

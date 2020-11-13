<?php

namespace Mcm\SalesforceClient\Request;

use Mcm\SalesforceClient\Enum\ContentType;
use Mcm\SalesforceClient\Enum\RequestMethod;

class Delete implements RequestInterface
{
    const ENDPOINT = '/sobjects/%s/%s/';

    protected string $objectType;

    protected string $id;

    public function __construct(string $objectType, string $id)
    {
        $this->objectType = $objectType;
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): RequestMethod
    {
        return RequestMethod::DELETE();
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint(): string
    {
        return sprintf(self::ENDPOINT, $this->objectType->value(), $this->id);
    }

    /**
     * {@inheritdoc}
     */
    public function getParams(): array
    {
        return [];
    }

    public function getContentType(): string
    {
        return ContentType::FORM;
    }
}

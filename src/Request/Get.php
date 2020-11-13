<?php

namespace Mcm\SalesforceClient\Request;

use Mcm\SalesforceClient\Enum\ContentType;
use Mcm\SalesforceClient\Enum\RequestMethod;

class Get implements RequestInterface
{
    const ENDPOINT = '/sobjects/%s/%s/';

    protected string $objectType;

    protected string $id;

    protected array $params;

    public function __construct(string $objectType, string $id, array $params = [])
    {
        $this->objectType = $objectType;
        $this->id = $id;
        $this->params = $params;
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
    public function getMethod(): RequestMethod
    {
        return RequestMethod::GET();
    }

    /**
     * {@inheritdoc}
     */
    public function getParams(): array
    {
        if (empty($this->params)) {
            return [];
        }

        return ['fields' => implode(',', $this->params)];
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType(): string
    {
        return ContentType::FORM;
    }
}

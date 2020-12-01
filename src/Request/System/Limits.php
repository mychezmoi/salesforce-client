<?php

namespace Mcm\SalesforceClient\Request\System;

use Mcm\SalesforceClient\Request\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

class Limits implements RequestInterface
{
    const ENDPOINT = 'limits/';

    /**
     * {@inheritdoc}
     */
    public function getEndpoint(): string
    {
        return self::ENDPOINT;
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
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function hasBody(): bool
    {
        return false;
    }
}

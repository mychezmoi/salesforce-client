<?php

namespace Mcm\SalesforceClient\Request\System;

use Mcm\SalesforceClient\Request\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

class Limits implements RequestInterface
{
    const ENDPOINT = 'limits/';


    public function getEndpoint(): string
    {
        return self::ENDPOINT;
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

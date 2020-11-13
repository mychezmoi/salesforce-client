<?php

namespace Mcm\SalesforceClient\Request;

use Mcm\SalesforceClient\Enum\ContentType;
use Mcm\SalesforceClient\Enum\RequestMethod;

interface RequestInterface
{
    public function getMethod(): string;

    public function getParams(): array;

    public function getEndpoint(): string;

    public function getContentType(): string;
}

<?php

namespace Mcm\SalesforceClient\Request;

interface RequestInterface
{
    public function getMethod(): string;

    public function getParams(): array;

    public function getEndpoint(): string;

    public function hasBody(): bool;
}

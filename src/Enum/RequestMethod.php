<?php

namespace Mcm\SalesforceClient\Enum;

class RequestMethod
{
    ///@todo: use Symfony\Component\HttpFoundation\Request

    const GET = 'GET';
    const POST = 'POST';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';
}

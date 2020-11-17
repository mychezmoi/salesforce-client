<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\From;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class ObjectType extends AbstractFrom implements ExprInterface
{
    private string $objectType;

    public function __construct(string $objectType)
    {
        $this->objectType = $objectType;
    }

    protected function getFromPart(): string
    {
        return $this->objectType;
    }
}

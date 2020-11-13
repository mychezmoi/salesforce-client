<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Compare;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;
use Mcm\SalesforceClient\QueryBuilder\Visitor\VisiteeInterface;

class Equals extends AbstractSingleCompare implements ExprInterface, VisiteeInterface
{
    public function getComparator(): string
    {
        return '=';
    }
}

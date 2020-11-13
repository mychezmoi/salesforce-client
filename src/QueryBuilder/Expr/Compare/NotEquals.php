<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Compare;

use Mcm\SalesforceClient\QueryBuilder\Visitor\VisiteeInterface;
use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class NotEquals extends AbstractSingleCompare implements ExprInterface, VisiteeInterface
{
    public function getComparator(): string
    {
        return '!=';
    }
}

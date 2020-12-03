<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Compare;

use Mcm\SalesforceClient\QueryBuilder\Visitor\VisitedInterface;
use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class GreaterThanOrEquals extends AbstractSingleCompare implements ExprInterface, VisitedInterface
{
    public function getComparator(): string
    {
        return '>=';
    }
}

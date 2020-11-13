<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Compare;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;
use Mcm\SalesforceClient\QueryBuilder\Visitor\VisiteeInterface;

class NotIn extends AbstractMultiCompare implements ExprInterface, VisiteeInterface
{
    public function getComparator(): string
    {
        return 'NOT IN';
    }
}

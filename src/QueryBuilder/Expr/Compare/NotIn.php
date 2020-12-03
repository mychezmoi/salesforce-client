<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Compare;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;
use Mcm\SalesforceClient\QueryBuilder\Visitor\VisitedInterface;

class NotIn extends AbstractMultiCompare implements ExprInterface, VisitedInterface
{
    public function getComparator(): string
    {
        return 'NOT IN';
    }
}

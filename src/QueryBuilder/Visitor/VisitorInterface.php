<?php

namespace Mcm\SalesforceClient\QueryBuilder\Visitor;

use Mcm\SalesforceClient\QueryBuilder\Expr\Compare\AbstractMultiCompare;
use Mcm\SalesforceClient\QueryBuilder\Expr\Compare\AbstractSingleCompare;

interface VisitorInterface
{
    public function visitSingleCompare(AbstractSingleCompare $compare);

    public function visitMultiCompare(AbstractMultiCompare $multiCompare);
}

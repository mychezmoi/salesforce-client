<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr;

interface ExprInterface
{
    public function asSOQL(): string;
}

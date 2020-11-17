<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Select\Typeof;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;
use Mcm\SalesforceClient\QueryBuilder\Expr\Select\Fields;

class ElseClause implements ExprInterface
{
    protected Fields $fields;

    public function __construct(Fields $fields)
    {
        $this->fields = $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function asSOQL(): string
    {
        return sprintf(' ELSE %s', $this->fields->asSOQL());
    }
}

<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\GroupBy;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class Fields extends AbstractGroupBy implements ExprInterface
{
    protected array $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }


    protected function getGroupByPart(): string
    {
        return implode(', ', $this->fields);
    }
}

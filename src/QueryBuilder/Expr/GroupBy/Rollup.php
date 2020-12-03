<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\GroupBy;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class Rollup extends AbstractGroupBy implements ExprInterface
{
    protected array $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }


    protected function getGroupByPart(): string
    {
        return sprintf('ROLLUP(%s)', implode(', ', $this->fields));
    }
}

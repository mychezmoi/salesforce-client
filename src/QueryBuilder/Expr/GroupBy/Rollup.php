<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\GroupBy;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class Rollup extends AbstractGroupBy implements ExprInterface
{
    /**
     * @var array
     */
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * {@inheritdoc}
     */
    protected function getGroupByPart(): string
    {
        return sprintf('ROLLUP(%s)', implode(', ', $this->fields));
    }
}

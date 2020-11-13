<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\GroupBy;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class Fields extends AbstractGroupBy implements ExprInterface
{
    /**
     * @var array
     */
    protected $fields;

    public function __construct(array $field)
    {
        $this->fields = $field;
    }

    /**
     * {@inheritdoc}
     */
    protected function getGroupByPart(): string
    {
        return implode(', ', $this->fields);
    }
}

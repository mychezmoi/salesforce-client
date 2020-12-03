<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\OrderBy;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class OrderBy extends AbstractOrderBy implements ExprInterface
{
    private array $fields;

    private Order $order;

    private Strategy $strategy;

    public function __construct(
        array $fields,
        Order $order = null,
        Strategy $strategy = null
    )
    {
        $this->fields   = $fields;
        $this->order    = null === $order ? Order::ASC : $order;
        $this->strategy = null === $strategy ? Strategy::NULLS_FIRST : $strategy;
    }


    protected function getFields(): array
    {
        return $this->fields;
    }


    protected function getOrder(): Order
    {
        return $this->order;
    }


    protected function getStrategy(): Strategy
    {
        return $this->strategy;
    }
}

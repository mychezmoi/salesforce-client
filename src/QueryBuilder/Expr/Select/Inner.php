<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Select;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;
use Mcm\SalesforceClient\QueryBuilder\Query;

class Inner extends AbstractSelect implements ExprInterface
{
    private Query $innerQuery;

    public function __construct(Query $query)
    {
        $this->innerQuery = $query;
    }

    protected function getSelectPart(): string
    {
        return sprintf('(%s)', $this->innerQuery->parse());
    }
}

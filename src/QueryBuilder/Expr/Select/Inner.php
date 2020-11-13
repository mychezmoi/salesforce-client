<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Select;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;
use Mcm\SalesforceClient\QueryBuilder\Query;

class Inner extends AbstractSelect implements ExprInterface
{
    /**
     * @var Query
     */
    private $innerQuery;

    public function __construct(Query $query)
    {
        $this->innerQuery = $query;
    }

    protected function getSelectPart(): string
    {
        return sprintf('(%s)', $this->innerQuery->parse());
    }
}

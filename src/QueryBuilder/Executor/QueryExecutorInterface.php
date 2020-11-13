<?php

namespace Mcm\SalesforceClient\QueryBuilder\Executor;

use Mcm\SalesforceClient\QueryBuilder\Query;
use Mcm\SalesforceClient\QueryBuilder\Records;

interface QueryExecutorInterface
{
    public function getRecords(Query $query): Records;

    /**
     * @param Records $records
     *
     * @return Records|null when there is no next records for given set
     */
    public function getNextRecords(Records $records);
}

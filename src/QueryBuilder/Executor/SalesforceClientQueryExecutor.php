<?php

namespace Mcm\SalesforceClient\QueryBuilder\Executor;

use Mcm\SalesforceClient\Client\SalesforceClient;
use Mcm\SalesforceClient\QueryBuilder\Query;
use Mcm\SalesforceClient\QueryBuilder\Records;
use Mcm\SalesforceClient\Request\Query as RequestQuery;
use Mcm\SalesforceClient\Request\QueryNext;

class SalesforceClientQueryExecutor implements QueryExecutorInterface
{
    private SalesforceClient $client;

    public function __construct(SalesforceClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \Exception
     */
    public function getRecords(Query $query): Records
    {
        $request = new RequestQuery($query->parse());
        $result = $this->client->doRequest($request);

        return new Records($result);
    }

    /**
     * @param Records $records
     *
     * @return Records|null
     */
    public function getNextRecords(Records $records)
    {
        if (!$records->hasNext()) {
            return null;
        }

        $request = new QueryNext($records->getNextIdentifier());
        $nextResult = $this->client->doRequest($request);

        return new Records($nextResult);
    }
}

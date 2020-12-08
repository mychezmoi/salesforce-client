<?php

namespace Mcm\SalesforceClient\QueryBuilder;

use Mcm\SalesforceClient\Client\SalesforceClient;
use Mcm\SalesforceClient\QueryBuilder\Query;
use Mcm\SalesforceClient\QueryBuilder\Records;
use Mcm\SalesforceClient\Request\Query as RequestQuery;
use Mcm\SalesforceClient\Request\QueryNext;

class QueryExecutor
{
    private SalesforceClient $client;

    private ?Records $records;

    public function __construct(SalesforceClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \Exception
     */
    public function getRecords(Query $query): Records
    {
        if ($this->records) {
            return $this->records;
        }

        $response = $this->client->query($query->parse());

        $this->records = new Records($response->getContent());

        return $this->records;
    }

    public function getFirstRecord (Query $query)
    {
        $records = $this->getRecords($query);

        return isset($records[0]) ? $records[0] : null;
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

        $response = $this->client->queryNext($records->getNextIdentifier());

        $this->records = new Records($response->getContent());

        return $this->records;
    }
}

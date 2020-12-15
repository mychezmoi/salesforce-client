ExpressionFactory, QueryBuilder and QueryExecutor
===

## Introduction

You can send "query" request using SalesforceClient

```php
$response = $salesforceClient->query('SELECT Name FROM Contact');
```

But it's not object oriented, you need to know how to create whole SOQL, may have execution errors due to malformed query etc

Thanks to `QueryBuilder` and `QueryExecutor` this process is Doctrine alike.

## Step 1 : QueryBuilder

```php
$e = new ExpressionFactory();

$query = (new QueryBuilder())
    ->select($e->fields([Contact::getNameField()]))
    ->from($e->objectType(Contact::getSName()))
    ->where($e->equals(Contact::getSIdField(), '{contactId}'))        
    ->setParameters(['contactId' => '000XXGEFEF0'])
    ->getQuery();
```

Many more methods are available, (inner join, group by, limit...)
* see [Query Builder](doc/QueryBuilder.md)
* see [Expression Factory](doc/ExpressionFactory.md)

## Step 2 : QueryExecutor

QueryExecutor allows to transfer a query instance to Records and optionally fetch next results.

```php
$queryExecutor = new QueryExecutor($salesforceClient);

$firstRecord =  $queryExecutor->getFirstRecord($query);

$records = $queryExecutor->getRecords($query);

do {
    foreach ($records as $record) {
        // 
    }
} while (($records = $queryExecutor->getNextRecords($records)) !== null);
```

[↑ Table of contents ↑](/doc/README.md)

<?php

namespace Mcm\SalesforceClient\QueryBuilder\Visitor;

interface VisitedInterface
{
    public function accept(VisitorInterface $visitor);

    public function update(array $values);
}

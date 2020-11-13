<?php

namespace Mcm\SalesforceClient\QueryBuilder\Visitor\Parameters;

interface ReplacingStrategyInterface
{
    public function replace($value): string;

    public function isApplicable(Type $type): bool;
}

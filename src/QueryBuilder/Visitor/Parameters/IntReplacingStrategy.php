<?php

namespace Mcm\SalesforceClient\QueryBuilder\Visitor\Parameters;

class IntReplacingStrategy implements ReplacingStrategyInterface
{
    public function isApplicable(string $type): bool
    {
        return Type::INT === $type;
    }

    public function replace($value): string
    {
        return (string)intval($value);
    }
}

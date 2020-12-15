<?php

namespace Mcm\SalesforceClient\QueryBuilder\Visitor\Parameters;

class FloatReplacingStrategy implements ReplacingStrategyInterface
{
    public function isApplicable(string $type): bool
    {
        return Type::FLOAT === $type;
    }

    public function replace($value): string
    {
        return (string)floatval($value);
    }
}

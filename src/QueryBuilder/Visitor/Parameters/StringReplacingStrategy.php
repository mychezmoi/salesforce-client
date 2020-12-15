<?php

namespace Mcm\SalesforceClient\QueryBuilder\Visitor\Parameters;

class StringReplacingStrategy implements ReplacingStrategyInterface
{
    public function isApplicable(string $type): bool
    {
        return Type::STRING === $type;
    }

    public function replace($value): string
    {
        return sprintf('\'%s\'', preg_quote($value, '\''));
    }
}

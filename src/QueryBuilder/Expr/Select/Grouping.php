<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Select;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class Grouping extends AbstractSelect implements ExprInterface
{
    protected string $fieldName;

    protected string $targetName;

    public function __construct(string $fieldName, string $targetName)
    {
        $this->fieldName  = $fieldName;
        $this->targetName = $targetName;
    }


    protected function getSelectPart(): string
    {
        return sprintf('GROUPING(%s) %s', $this->fieldName, $this->targetName);
    }
}

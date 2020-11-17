<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Select;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class Fields extends AbstractSelect implements ExprInterface
{
    /**
     * @var string[]
     */
    private array $fields = [];

    /**
     * @param string[] $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    protected function getSelectPart(): string
    {
        return implode(', ', $this->fields);
    }
}

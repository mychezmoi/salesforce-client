<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Select\Typeof;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;
use Mcm\SalesforceClient\QueryBuilder\Expr\Select\Fields;

class WhenClause implements ExprInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var Fields
     */
    protected $fields;

    public function __construct(string $type, Fields $fields)
    {
        $this->type = $type;
        $this->fields = $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function asSOQL(): string
    {
        return sprintf(' WHEN %s THEN %s', $this->type, $this->fields->asSOQL());
    }
}

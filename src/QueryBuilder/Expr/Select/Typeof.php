<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Select;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;
use Mcm\SalesforceClient\QueryBuilder\Expr\Select\Typeof\ElseClause;
use Mcm\SalesforceClient\QueryBuilder\Expr\Select\Typeof\WhenClause;

class Typeof extends AbstractSelect implements ExprInterface
{
    protected string $field;

    /**
     * @var WhenClause[]
     */
    protected array $whenClauses;

    /**
     * @var ElseClause|null
     */
    protected ?ElseClause $elseClause = null;

    public function __construct(string $field)
    {
        $this->field       = $field;
        $this->whenClauses = [];
    }

    public function when(string $field, Fields $fields): self
    {
        $this->whenClauses[] = new WhenClause($field, $fields);

        return $this;
    }

    public function else(Fields $fields): self
    {
        $this->elseClause = new ElseClause($fields);

        return $this;
    }


    protected function getSelectPart(): string
    {
        $result = sprintf('TYPEOF %s%s', $this->field, $this->getWhen());

        if (null === $this->elseClause) {
            return sprintf('%s END', $result);
        }

        return sprintf('%s%s END', $result, $this->elseClause->asSOQL());
    }

    protected function getWhen(): string
    {
        $when = '';

        foreach ($this->whenClauses as $whenClause) {
            $when .= $whenClause->asSOQL();
        }

        return $when;
    }
}

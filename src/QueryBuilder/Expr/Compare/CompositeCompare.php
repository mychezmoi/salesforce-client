<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Compare;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;
use Mcm\SalesforceClient\QueryBuilder\Visitor\VisitedInterface;
use Mcm\SalesforceClient\QueryBuilder\Visitor\VisitorInterface;

class CompositeCompare extends AbstractCompare implements ExprInterface, VisitedInterface
{
    private AbstractCompare $leftExpr;

    private string $operator;

    private AbstractCompare $rightExpr;

    private bool $wrapPrevious;

    public function __construct(
        AbstractCompare $leftExpr,
        string $operator,
        AbstractCompare $rightExpr,
        bool $wrapPrevious = false
    )
    {
        $this->leftExpr     = $leftExpr;
        $this->operator     = $operator;
        $this->rightExpr    = $rightExpr;
        $this->wrapPrevious = $wrapPrevious;
    }


    public function getComparator(): string
    {
        return $this->operator;
    }


    public function getLeft(): string
    {
        if ($this->wrapPrevious && $this->leftExpr instanceof self) {
            return sprintf('(%s)', $this->leftExpr->asSOQL());
        }

        return $this->leftExpr->asSOQL();
    }


    public function getRight(): string
    {
        return $this->rightExpr->asSOQL();
    }

    public function accept(VisitorInterface $visitor)
    {
        if ($this->leftExpr instanceof VisitedInterface) {
            $this->leftExpr->accept($visitor);
        }

        if ($this->rightExpr instanceof VisitedInterface) {
            $this->rightExpr->accept($visitor);
        }
    }

    /**
     * Inner expressions will be updated by reference.
     */
    public function update(array $values)
    {
    }
}

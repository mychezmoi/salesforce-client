<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Compare;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;
use Mcm\SalesforceClient\QueryBuilder\Visitor\VisiteeInterface;
use Mcm\SalesforceClient\QueryBuilder\Visitor\VisitorInterface;

class CompositeCompare extends AbstractCompare implements ExprInterface, VisiteeInterface
{
    /**
     * @var AbstractCompare
     */
    private $leftExpr;

    /**
     * @var Operator
     */
    private $operator;

    /**
     * @var AbstractCompare
     */
    private $rightExpr;

    /**
     * @var bool
     */
    private $wrapPrevious;

    public function __construct(AbstractCompare $leftExpr, Operator $operator, AbstractCompare $rightExpr, bool $wrapPrevious = false)
    {
        $this->leftExpr = $leftExpr;
        $this->operator = $operator;
        $this->rightExpr = $rightExpr;
        $this->wrapPrevious = $wrapPrevious;
    }

    /**
     * {@inheritdoc}
     */
    public function getComparator(): string
    {
        return $this->operator->value();
    }

    /**
     * {@inheritdoc}
     */
    public function getLeft(): string
    {
        if ($this->wrapPrevious && $this->leftExpr instanceof self) {
            return sprintf('(%s)', $this->leftExpr->asSOQL());
        }

        return $this->leftExpr->asSOQL();
    }

    /**
     * {@inheritdoc}
     */
    public function getRight(): string
    {
        return $this->rightExpr->asSOQL();
    }

    public function accept(VisitorInterface $visitor)
    {
        if ($this->leftExpr instanceof VisiteeInterface) {
            $this->leftExpr->accept($visitor);
        }

        if ($this->rightExpr instanceof VisiteeInterface) {
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

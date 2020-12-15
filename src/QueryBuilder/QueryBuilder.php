<?php

namespace Mcm\SalesforceClient\QueryBuilder;

use Mcm\SalesforceClient\QueryBuilder\Expr\Compare\AbstractCompare;
use Mcm\SalesforceClient\QueryBuilder\Expr\Compare\CompositeCompare;
use Mcm\SalesforceClient\QueryBuilder\Expr\Compare\Operator;
use Mcm\SalesforceClient\QueryBuilder\Expr\From\AbstractFrom;
use Mcm\SalesforceClient\QueryBuilder\Expr\GroupBy\AbstractGroupBy;
use Mcm\SalesforceClient\QueryBuilder\Expr\OrderBy\AbstractOrderBy;
use Mcm\SalesforceClient\QueryBuilder\Expr\Select\AbstractSelect;

class QueryBuilder
{
    private Query $query;

    public function __construct()
    {
        $this->query = new Query();
    }

    public function select(AbstractSelect ...$selects): self
    {
        foreach ($selects as $select) {
            $this->query->addSelect($select);
        }

        return $this;
    }

    public function from(AbstractFrom $from): self
    {
        $this->query->setFrom($from);

        return $this;
    }

    public function where(AbstractCompare $where): self
    {
        $this->query->setWhere($where);

        return $this;
    }

    public function andWhere(AbstractCompare $where, bool $wrapPrevious = false): self
    {
        $this->addOrUpdateWhere($where, Operator::CONJUNCTION, $wrapPrevious);

        return $this;
    }

    public function orWhere(AbstractCompare $where, bool $wrapPrevious = false): self
    {
        $this->addOrUpdateWhere($where, Operator::DISJUNCTION, $wrapPrevious);

        return $this;
    }

    public function groupBy(AbstractGroupBy $groupBy): self
    {
        $this->query->setGroupBy($groupBy);

        return $this;
    }

    public function having(AbstractCompare $having): self
    {
        $this->query->setHaving($having);

        return $this;
    }

    public function andHaving(AbstractCompare $having, bool $wrapPrevious = false): self
    {
        $this->addOrUpdateHaving($having, Operator::CONJUNCTION, $wrapPrevious);

        return $this;
    }

    public function orHaving(AbstractCompare $having, bool $wrapPrevious = false): self
    {
        $this->addOrUpdateHaving($having, Operator::DISJUNCTION, $wrapPrevious);

        return $this;
    }

    private function addOrUpdateWhere(AbstractCompare $where, string $operator, bool $wrapPrevious)
    {
        $this->query->setWhere(
            $this->createCompositeCompare(
                $where,
                $operator,
                $wrapPrevious,
                $this->query->getWhere()
            )
        );
    }

    private function addOrUpdateHaving(AbstractCompare $having, string $operator, bool $wrapPrevious)
    {
        $this->query->setHaving(
            $this->createCompositeCompare(
                $having,
                $operator,
                $wrapPrevious,
                $this->query->getHaving()
            )
        );
    }

    private function createCompositeCompare(
        AbstractCompare $compare,
        string $operator,
        bool $wrapPrevious,
        AbstractCompare $currentCompare = null
    ): AbstractCompare
    {
        if (null === $currentCompare) {
            return $compare;
        }

        return new CompositeCompare($currentCompare, $operator, $compare, $wrapPrevious);
    }

    public function orderBy(AbstractOrderBy $orderBy): self
    {
        $this->query->setOrderBy($orderBy);

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->query->setLimit($limit);

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->query->setOffset($offset);

        return $this;
    }

    public function setParameters(array $parameters): self
    {
        $this->query->setParameters($parameters);

        return $this;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }

    public function formatDateTime(\DateTime $dateTime)
    {
        return $dateTime->format('Y-m-d\TH:i:s\Z');
    }
}

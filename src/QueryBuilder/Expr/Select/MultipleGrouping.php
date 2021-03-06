<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Select;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class MultipleGrouping extends AbstractSelect implements ExprInterface
{
    /**
     * @var Grouping[]
     */
    protected array $groupings;

    /**
     * @param Grouping[] $groupings
     */
    public function __construct(array $groupings)
    {
        $this->groupings = $groupings;
    }


    protected function getSelectPart(): string
    {
        $result = [];

        foreach ($this->groupings as $group) {
            $result[] = $group->asSOQL();
        }

        return implode(', ', $result);
    }
}

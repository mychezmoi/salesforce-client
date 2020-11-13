<?php

namespace Mcm\SalesforceClient\QueryBuilder\Expr\Select;

use Mcm\SalesforceClient\QueryBuilder\Expr\ExprInterface;

class Count extends AbstractSelect implements ExprInterface
{
    /**
     * @var string|null
     */
    private $countedValue;

    public function __construct(string $countedValue = null)
    {
        $this->countedValue = $countedValue;
    }

    protected function getSelectPart(): string
    {
        if (null !== $this->countedValue) {
            return sprintf('COUNT(%s)', $this->countedValue);
        }

        return 'COUNT()';
    }
}

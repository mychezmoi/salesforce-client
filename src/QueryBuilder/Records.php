<?php

namespace Mcm\SalesforceClient\QueryBuilder;

/**
 * @todo if the executor was injected to query builder this might have iterated over the next results itself, check if this makes sense
 */
class Records implements \IteratorAggregate, \Countable
{
    private array $raw;

    public function __construct(array $raw)
    {
        $this->raw = $raw;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->raw['records']);
    }

    public function getFirst()
    {
        return isset($this->raw['records']) && isset($this->raw['records'][0]) ? $this->raw['records'][0] : null;
    }

    public function count(): int
    {
        return count($this->raw['records']);
    }

    public function hasNext(): bool
    {
        return isset($this->raw['nextRecordsUrl']);
    }

    public function getNextIdentifier(): string
    {
        if (!$this->hasNext()) {
            throw new \LogicException('Result has no next page');
        }

        $uriParts = explode('/', $this->raw['nextRecordsUrl']);

        return end($uriParts);
    }

    public function getTotalSize(): int
    {
        return $this->raw['totalSize'];
    }
}

<?php

namespace Mcm\SalesforceClient\Repository;

use Mcm\SalesforceClient\AbstractSObject;

interface SObjectRepositoryInterface
{
    public function create(AbstractSObject $object);

    public function update(AbstractSObject $object);

    public function delete(AbstractSObject $object);

    public function find(string $class, string $id): AbstractSObject;

    public function getFieldValues(string $class, string $id, array $fields = []): array;
}

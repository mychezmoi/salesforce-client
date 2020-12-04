<?php

namespace Mcm\SalesforceClient;

abstract class AbstractSObject
{
    protected string $id;

    protected string $createdById;

    protected \DateTime $createdDate;

    protected string $lastModifiedById;

    protected \DateTime $lastModifiedDate;

    protected \DateTime $systemModstamp;

    abstract public static function getSName(): string;

    public static function getSIdField()
    {
        return 'Id';
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedById(): string
    {
        return $this->createdById;
    }

    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    public function getLastModifiedById(): string
    {
        return $this->lastModifiedById;
    }

    public function getLastModifiedDate(): \DateTime
    {
        return $this->lastModifiedDate;
    }

    public function getSystemModstamp(): \DateTime
    {
        return $this->systemModstamp;
    }
}

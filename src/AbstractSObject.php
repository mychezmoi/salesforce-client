<?php

namespace Mcm\SalesforceClient;

abstract class AbstractSObject
{
    protected string $sId;

    protected ?array $content = [];

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

    public function getSId(): string
    {
        return $this->sId;
    }

    public function setSId(string $sId): self
    {
        $this->sId = $sId;

        return $this;
    }

    public function setContent(array $content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function get($field)
    {
        return isset($this->content[$field]) ? $this->content[$field] : null;
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

<?php

namespace Mcm\SalesforceClient;

abstract class AbstractSObject
{
    protected string $sId;

    protected ?array $content = [];

    abstract public static function getSName(): string;

    public static function getSIdField(): string
    {
        return 'Id';
    }

    public static function getCreatedByField(): string
    {
        return 'CreatedBy';
    }

    public static function getCreatedDateField(): string
    {
        return 'CreatedDate';
    }

    public static function getLastModifiedByField(): string
    {
        return 'LastModifiedById';
    }

    public static function getLastModifiedDateField(): string
    {
        return 'LastModifiedDate';
    }

    public static function getNameField(): string
    {
        return 'Name';
    }

    public static function getOwnerIdField(): string
    {
        return 'OwnerId';
    }

    public static function getSystemModstampField(): string
    {
        return 'SystemModStamp';
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

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function get(string $field): ?string
    {
        return isset($this->content[$field]) ? $this->content[$field] : null;
    }

    public function set(string $field, $value): void
    {
        $this->content[$field] = $value;
    }

    public function getCreatedBy(): string
    {
        return $this->get(self::getCreatedByField());
    }

    public function getCreatedDate(): \DateTime
    {
        return new \DateTime($this->get(self::getCreatedDateField()));
    }

    public function getLastModifiedBy(): string
    {
        return $this->get(self::getLastModifiedByField());
    }

    // last modified by a User
    public function getLastModifiedDate(): \DateTime
    {
        return new \DateTime($this->get(self::getLastModifiedDateField()));
    }

    public function getName(): string
    {
        return $this->get(self::getNameField());
    }

    public function getOwnerId(): string
    {
        return $this->get(self::getOwnerIdField());
    }

    // last modified by a User or by an automated process such as a trigger
    public function getSystemModstamp(): \DateTime
    {
        return new \DateTime($this->get(self::getSystemModstampField()));
    }
}

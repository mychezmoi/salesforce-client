<?php

namespace Mcm\SalesforceClient;

use JMS\Serializer\Annotation\Type;

abstract class AbstractSObject
{
    /**
     * @Type("string")
     */
    protected string $id;

    /**
     * @Type("string")
     */
    protected string $createdById;

    /**
     * @Type("DateTime<'Y-m-d\TH:i:s.\0\0\0O'>")
     */
    protected \DateTime $createdDate;

    /**
     * @Type("string")
     */
    protected string $lastModifiedById;

    /**
     * @Type("DateTime<'Y-m-d\TH:i:s.\0\0\0O'>")
     */
    protected \DateTime $lastModifiedDate;

    /**
     * @Type("DateTime<'Y-m-d\TH:i:s.\0\0\0O'>")
     */
    protected \DateTime $systemModstamp;

    abstract public static function getSName(): string;

    public function getId(): string
    {
        return $this->id;
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

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}

<?php

namespace Mcm\SalesforceClient\Repository;

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\SerializationContext;
use Mcm\SalesforceClient\Client\SalesforceClient;
use Mcm\SalesforceClient\Model\AbstractSObject;
use Mcm\SalesforceClient\Request\Create;
use Mcm\SalesforceClient\Request\Delete;
use Mcm\SalesforceClient\Request\Get;
use Mcm\SalesforceClient\Request\Update;

class SObjectRepository implements SObjectRepositoryInterface
{
    const GROUP_UPDATE = 'update';
    const GROUP_CREATE = 'create';

    protected SalesforceClient $client;

    protected ArrayTransformerInterface $serializer;

    public function __construct(SalesforceClient $client, ArrayTransformerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @throws \Exception
     */
    public function create(AbstractSObject $object)
    {
        $response = $this->client->doRequest(new Create(
            $object::getSObjectName(),
            $this->serializer->toArray($object, SerializationContext::create()->setGroups([self::GROUP_CREATE]))
        ));

        if (!$response['success']) {
            return;
        }

        $object->setId($response['id']);
    }

    /**
     * @throws \Exception
     */
    public function delete(AbstractSObject $object)
    {
        $this->client->doRequest(new Delete($object::getSObjectName(), $object->getId()));
    }

    /**
     * @throws \Exception
     */
    public function update(AbstractSObject $object)
    {
        $this->client->doRequest(new Update(
            $object::getSObjectName(),
            $object->getId(),
            $this->serializer->toArray($object, SerializationContext::create()->setGroups([self::GROUP_UPDATE])->setSerializeNull(true))
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $class, string $id): AbstractSObject
    {
        return $this->serializer->fromArray(
            $this->getFieldValues($class, $id),
            $class
        );
    }

    /**
     * @throws \Exception
     */
    public function getFieldValues(string $class, string $id, array $fields = []): array
    {
        if (!is_a($class, AbstractSObject::class, true)) {
            throw new \RuntimeException(sprintf('%s should extend %s', $class, AbstractSObject::class));
        }

        return $this->client->doRequest(new Get($class::getSObjectName(), $id, $fields));
    }
}

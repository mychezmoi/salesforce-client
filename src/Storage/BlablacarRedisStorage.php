<?php

namespace Mcm\SalesforceClient\Storage;

use Blablacar\Redis\Client;
use Mcm\SalesforceClient\Security\Token\TokenInterface;

class BlablacarRedisStorage implements TokenStorageInterface
{
    const DEFAULT_KEY = 'salesforce_token';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $key;

    public function __construct(Client $client, string $key = self::DEFAULT_KEY)
    {
        $this->client = $client;
        $this->key = $key;
    }

    /**
     * @return TokenInterface
     *
     * @throws \LogicException if the token is not set
     */
    public function get(): TokenInterface
    {
        $token = $this->client->get($this->key);

        if (null === $token) {
            throw new \LogicException('No token has been set');
        }

        return unserialize($token);
    }

    public function has(): bool
    {
        return (bool) $this->client->exists($this->key);
    }

    public function save(TokenInterface $token)
    {
        $this->client->set($this->key, serialize($token));
    }
}

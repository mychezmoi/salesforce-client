<?php

namespace Mcm\SalesforceClient\Storage;

use Mcm\SalesforceClient\Security\Token\TokenInterface;

class ApcuTokenStorage implements TokenStorageInterface
{
    const TOKEN_NAME = 'salesforce_token';

    /**
     * @return TokenInterface
     *
     * @throws \LogicException if the token is not set
     */
    public function get(): TokenInterface
    {
        if (!$this->has()) {
            throw new \LogicException('No token has been set');
        }

        return unserialize(apcu_fetch(self::TOKEN_NAME, $success));
    }


    public function has(): bool
    {
        apcu_fetch(self::TOKEN_NAME, $success);

        return $success;
    }


    public function save(TokenInterface $token)
    {
        apcu_store(self::TOKEN_NAME, serialize($token));
    }
}

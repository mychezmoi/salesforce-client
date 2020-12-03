<?php

namespace Mcm\SalesforceClient\Storage;

use Mcm\SalesforceClient\Security\Token\TokenInterface;

class RequestTokenStorage implements TokenStorageInterface
{
    protected ?TokenInterface $token = null;

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

        return $this->token;
    }


    public function has(): bool
    {
        return $this->token instanceof TokenInterface;
    }


    public function save(TokenInterface $token)
    {
        $this->token = $token;
    }
}

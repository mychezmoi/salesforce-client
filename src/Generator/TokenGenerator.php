<?php

namespace Mcm\SalesforceClient\Generator;

use Mcm\SalesforceClient\Security\Authentication\AuthenticatorInterface;
use Mcm\SalesforceClient\Security\Authentication\Credentials;
use Mcm\SalesforceClient\Security\Token\TokenInterface;
use Mcm\SalesforceClient\Storage\TokenStorageInterface;

class TokenGenerator implements TokenGeneratorInterface
{
    protected Credentials $credentials;

    protected AuthenticatorInterface $authenticator;

    protected TokenStorageInterface $tokenStorage;

    public function __construct(
        Credentials $credentials,
        AuthenticatorInterface $authenticator,
        TokenStorageInterface $tokenStorage
    ) {
        $this->credentials = $credentials;
        $this->authenticator = $authenticator;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken(): TokenInterface
    {
        if ($this->tokenStorage->has()) {
            return $this->tokenStorage->get();
        }

        $token = $this->authenticator->authenticate($this->credentials);
        $this->tokenStorage->save($token);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function regenerateToken(TokenInterface $token): TokenInterface
    {
        $newToken = $this->authenticator->regenerate($this->credentials, $token);
        $this->tokenStorage->save($newToken);

        return $newToken;
    }
}

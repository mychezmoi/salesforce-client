<?php

namespace Mcm\SalesforceClient\Security\Token;

use Mcm\SalesforceClient\Security\Authentication\AuthenticatorInterface;
use Mcm\SalesforceClient\Security\Authentication\Credentials;
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
    )
    {
        $this->credentials   = $credentials;
        $this->authenticator = $authenticator;
        $this->tokenStorage  = $tokenStorage;
    }


    public function getToken(): TokenInterface
    {
        if ($this->tokenStorage->has()) {
            return $this->tokenStorage->get();
        }

        $token = $this->authenticator->authenticate($this->credentials);
        $this->tokenStorage->save($token);

        return $token;
    }


    public function regenerateToken(TokenInterface $token): TokenInterface
    {
        $newToken = $this->authenticator->regenerate($this->credentials, $token);
        $this->tokenStorage->save($newToken);

        return $newToken;
    }
}

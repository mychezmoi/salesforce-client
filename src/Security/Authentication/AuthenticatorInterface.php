<?php

namespace Mcm\SalesforceClient\Security\Authentication;

use Mcm\SalesforceClient\Security\Token\TokenInterface;

interface AuthenticatorInterface
{
    /**
     * @param Credentials $credentials
     *
     * @return TokenInterface
     */
    public function authenticate(Credentials $credentials): TokenInterface;

    /**
     * @param Credentials $credentials
     * @param TokenInterface $token
     *
     * @return TokenInterface
     *
     * @throws Exception\AuthenticationFailedException
     */
    public function regenerate(Credentials $credentials, TokenInterface $token): TokenInterface;
}

<?php

namespace Mcm\SalesforceClient\Security\Token;

interface TokenGeneratorInterface
{
    /**
     * @return TokenInterface
     */
    public function getToken(): TokenInterface;

    /**
     * @param TokenInterface $token
     *
     * @return TokenInterface
     */
    public function regenerateToken(TokenInterface $token): TokenInterface;
}

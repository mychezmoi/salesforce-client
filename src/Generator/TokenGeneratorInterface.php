<?php

namespace Mcm\SalesforceClient\Generator;

use Mcm\SalesforceClient\Security\Token\TokenInterface;

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

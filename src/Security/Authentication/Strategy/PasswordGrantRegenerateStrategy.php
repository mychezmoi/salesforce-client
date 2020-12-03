<?php

namespace Mcm\SalesforceClient\Security\Authentication\Strategy;

use Mcm\SalesforceClient\Security\Authentication\Credentials;
use Mcm\SalesforceClient\Security\Token\TokenInterface;

class PasswordGrantRegenerateStrategy implements RegenerateStrategyInterface
{

    public function getCredentials(Credentials $credentials, TokenInterface $token): Credentials
    {
        return $credentials;
    }


    public function supports(Credentials $credentials, TokenInterface $token): bool
    {
        return 'password' === $credentials->getGrantType();
    }
}

<?php

namespace Mcm\SalesforceClient\Security\Token;

/**
 * Basic implementation of TokenInterface.
 */
class Token implements TokenInterface
{
    protected string $tokenType;

    protected string $accessToken;

    protected string $refreshToken;

    protected string $instanceUrl;

    /**
     * @param string $tokenType
     * @param string $accessToken
     * @param string $instanceUrl
     * @param string $refreshToken
     */
    public function __construct(string $tokenType, string $accessToken, string $instanceUrl, string $refreshToken = '')
    {
        $this->tokenType    = $tokenType;
        $this->accessToken  = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->instanceUrl  = $instanceUrl;
    }


    public function getTokenType(): string
    {
        return $this->tokenType;
    }


    public function getAccessToken(): string
    {
        return $this->accessToken;
    }


    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }


    public function getInstanceUrl(): string
    {
        return $this->instanceUrl;
    }


    public function serialize(): string
    {
        return serialize(
            [
                $this->tokenType,
                $this->accessToken,
                $this->instanceUrl,
                $this->refreshToken,
            ]
        );
    }


    public function unserialize($serialized)
    {
        [
            $this->tokenType,
            $this->accessToken,
            $this->instanceUrl,
            $this->refreshToken,
        ] = unserialize($serialized);
    }
}

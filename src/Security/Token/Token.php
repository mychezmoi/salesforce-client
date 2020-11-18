<?php

namespace Mcm\SalesforceClient\Security\Token;

/**
 * Basic implementation of TokenInterace.
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

    /**
     * {@inheritdoc}
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getInstanceUrl(): string
    {
        return $this->instanceUrl;
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

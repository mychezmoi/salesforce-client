<?php

namespace Mcm\SalesforceClient\Security\Authentication;

class Credentials
{
    private string $clientId;

    private string $clientSecret;

    private string $grantType;

    private array $extraParams;

    public function __construct(string $clientId, string $clientSecret, string $grantType, array $extraParams = [])
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->grantType    = $grantType;
        $this->extraParams  = $extraParams;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getGrantType(): string
    {
        return $this->grantType;
    }

    public function getParameters(): array
    {
        return [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type'    => $this->grantType,
            ] + $this->extraParams;
    }
}

<?php

namespace Mcm\SalesforceClient\Security\Authentication;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

use Mcm\SalesforceClient\Security\Authentication\Strategy\RegenerateStrategyInterface;
use Mcm\SalesforceClient\Security\Token\Token;
use Mcm\SalesforceClient\Security\Token\TokenInterface;

class Authenticator implements AuthenticatorInterface
{
    const TOKEN_URL = 'https://login.salesforce.com/services/oauth2/token';

    protected HttpClientInterface $client;

    /**
     * @var RegenerateStrategyInterface[]
     */
    protected array $regenerateStrategies;

    /**
     * @param RegenerateStrategyInterface[] $regenerateStrategies
     */
    public function __construct(array $regenerateStrategies = [])
    {
        $this->client               = HttpClient::create();
        $this->regenerateStrategies = $regenerateStrategies;
    }


    public function authenticate(Credentials $credentials): TokenInterface
    {
        try {
            $response = $this->client->request(
                Request::METHOD_POST,
                self::TOKEN_URL,
                [
                    'headers' => [
                        'Content-type' => 'application/x-www-form-urlencoded',
                    ],
                    'body'    => $credentials->getParameters(),
                ]
            )->toArray();

        } catch (\Exception $e) {
            throw new Exception\AuthenticationRequestException('Authentication request failed.', 400, $e);
        }

        if (!$this->hasRequiredFields($response)) {
            throw new Exception\InvalidAuthenticationResponseException(
                'Response does not contains required fields: token_type, access_token, instance_url.'
            );
        }

        return new Token(
            $response['token_type'],
            $response['access_token'],
            $response['instance_url'],
            isset($response['refresh_token']) ? $response['refresh_token'] : ''
        );
    }


    public function regenerate(Credentials $credentials, TokenInterface $token): TokenInterface
    {
        foreach ($this->regenerateStrategies as $strategy) {
            if ($strategy->supports($credentials, $token)) {
                return $this->authenticate($strategy->getCredentials($credentials, $token));
            }
        }

        throw new Exception\UnsupportedCredentialsException('Strategy not found for given credentials and token.');
    }

    protected function hasRequiredFields(array $array): bool
    {
        if (!isset($array['token_type'])) {
            return false;
        }

        if (!isset($array['access_token'])) {
            return false;
        }

        if (!isset($array['instance_url'])) {
            return false;
        }

        return true;
    }
}

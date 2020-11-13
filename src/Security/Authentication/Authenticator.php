<?php

namespace Mcm\SalesforceClient\Security\Authentication;

use GuzzleHttp\Psr7\Request;
use Http\Client\Exception\HttpException;
use Http\Client\HttpClient;
use Mcm\SalesforceClient\Security\Authentication\Strategy\RegenerateStrategyInterface;
use Mcm\SalesforceClient\Security\Token\Token;
use Mcm\SalesforceClient\Security\Token\TokenInterface;

class Authenticator implements AuthenticatorInterface
{
    const ENDPOINT = '/services/oauth2/token';

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var RegenerateStrategyInterface[]
     */
    protected $regenerateStrategies;

    /**
     * @param HttpClient                    $client
     * @param RegenerateStrategyInterface[] $regenerateStrategies
     */
    public function __construct(HttpClient $client, array $regenerateStrategies = [])
    {
        $this->client = $client;
        $this->regenerateStrategies = $regenerateStrategies;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(Credentials $credentials): TokenInterface
    {
        try {
            $response = $this->client->sendRequest($this->getRequest($credentials))->getBody();
        } catch (HttpException $e) {
            throw new Exception\AuthenticationRequestException('Authentication request failed.', 400, $e);
        }

        $parsedBody = $this->parse($response);

        return new Token(
            $parsedBody['token_type'],
            $parsedBody['access_token'],
            $parsedBody['instance_url'],
            isset($parsedBody['refresh_token']) ? $parsedBody['refresh_token'] : ''
        );
    }

    /**
     * @throws Exception\InvalidAuthenticationResponseException
     */
    private function parse($rawResponse): array
    {
        $parsedBody = json_decode($rawResponse, true);

        if (!$parsedBody) {
            throw new Exception\InvalidAuthenticationResponseException(
                sprintf('Cannot decode response: %s', $rawResponse)
            );
        }

        if (!$this->hasRequiredFields($parsedBody)) {
            throw new Exception\InvalidAuthenticationResponseException(
                'Response does not contains required fields: token_type, access_token, instance_url.'
            );
        }

        return $parsedBody;
    }

    /**
     * {@inheritdoc}
     */
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

    protected function getRequest(Credentials $credentials): Request
    {
        return new Request(
            \Mcm\SalesforceClient\Enum\RequestMethod::POST()->value(),
            self::ENDPOINT,
            ['Content-type' => \Mcm\SalesforceClient\Enum\ContentType::FORM()->value()],
            http_build_query($credentials->getParameters())
        );
    }
}

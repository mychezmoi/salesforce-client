<?php

namespace Mcm\SalesforceClient\Client;

use Mcm\SalesforceClient\Request\RequestInterface;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

use Mcm\SalesforceClient\Enum\ContentType;
use Mcm\SalesforceClient\Generator\TokenGeneratorInterface;
use Mcm\SalesforceClient\Security\Token\TokenInterface;

class SalesforceClient
{
    const UNAUTHORIZED = 401;
    const PREFIX = 'services/data/';

    protected HttpClient $client;

    protected TokenGeneratorInterface $tokenManager;

    protected string $version;

    public function __construct(HttpClient $client, TokenGeneratorInterface $tokenManager, string $version)
    {
        $this->client = $client;
        $this->tokenManager = $tokenManager;
        $this->version = $version;
    }

    public function doRequest(RequestInterface $request): array
    {
        $token = $this->tokenManager->getToken();

        try {
            $response = $this->sendRequest($token, $request);
        } catch (\Exception $ex) {
            // Token is expired or invalid - get new and retry
            if (self::UNAUTHORIZED !== $ex->getCode()) {
                throw $ex;
            }

            $response = $this->sendRequest($this->tokenManager->regenerateToken($token), $request);
        }

        return $response->toArray();
    }

    protected function sendRequest(TokenInterface $token, RequestInterface $request): ResponseInterface
    {
        if ((string) $request->getContentType() === (string) ContentType::JSON) {
            $content = ['json' => $request->getParams()];
        }

        if ((string) $request->getContentType() === (string) ContentType::FORM) {
            $content = ['body' => $request->getParams()];
        }

        $client = HttpClient::create();
        $response = $client->request(
            $request->getMethod(),
            $this->getUri($token, $request),
            [
                'headers' => [
                    'authorization' => sprintf('%s %s', $token->getTokenType(), $token->getAccessToken()),
                    'Content-type' => $request->getContentType()
                ],
                $content
            ]
        );

        return $response;
    }

    protected function getUri(TokenInterface $token, RequestInterface $request): string
    {
        return sprintf(
            '%s/%s',
            rtrim($token->getInstanceUrl(), '/'),
            sprintf('%s%s/%s', self::PREFIX, $this->version, ltrim($request->getEndpoint(), '/'))
        );
    }
}

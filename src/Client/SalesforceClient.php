<?php

namespace Mcm\SalesforceClient\Client;

use Mcm\SalesforceClient\Request\RequestInterface;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

use Mcm\SalesforceClient\Enum\ContentType;
use Mcm\SalesforceClient\Generator\TokenGeneratorInterface;
use Mcm\SalesforceClient\Security\Token\TokenInterface;

class SalesforceClient
{
    const PREFIX = 'services/data/';

    protected HttpClientInterface $client;

    protected TokenGeneratorInterface $tokenManager;

    protected string $version;

    public function __construct(TokenGeneratorInterface $tokenManager, string $version)
    {
        $this->client       = HttpClient::create();
        $this->tokenManager = $tokenManager;
        $this->version      = $version;
    }

    public function doRequest(RequestInterface $request): array
    {
        $token = $this->tokenManager->getToken();

        try {
            $response = $this->sendRequest($token, $request);

            // Token is expired or invalid - get new and retry
            if ($response->getStatusCode() == Response::HTTP_UNAUTHORIZED) {
                $response = $this->sendRequest($this->tokenManager->regenerateToken($token), $request);
            }

            return $response->toArray();

        } catch (\Exception $ex) {

            //@todo: handle exceptions

            throw $ex;
        }
    }

    protected function sendRequest(TokenInterface $token, RequestInterface $request): ResponseInterface
    {
        if ((string)$request->getContentType() === (string)ContentType::JSON) {
            $content = ['json' => $request->getParams()];
        }

        if ((string)$request->getContentType() === (string)ContentType::FORM) {
            $content = ['body' => $request->getParams()];
        }

        $response = $this->client->request(
            $request->getMethod(),
            $this->getUri($token, $request),
            [
                'headers' => [
                    'authorization' => sprintf('%s %s', $token->getTokenType(), $token->getAccessToken()),
                    'Content-type'  => $request->getContentType(),
                ],
                $content,
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

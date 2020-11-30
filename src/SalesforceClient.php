<?php

namespace Mcm\SalesforceClient;

use Mcm\SalesforceClient\Request\RequestInterface;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

use Mcm\SalesforceClient\Request;
use Mcm\SalesforceClient\Generator\TokenGeneratorInterface;
use Mcm\SalesforceClient\Security\Token\TokenInterface;

class SalesforceClient
{
    const PREFIX = 'services/data';

    protected HttpClientInterface $client;

    protected TokenGeneratorInterface $tokenManager;

    protected string $version;

    protected ?LoggerInterface $logger = null;

    public function __construct(TokenGeneratorInterface $tokenManager, string $version)
    {
        $this->client       = HttpClient::create();
        $this->tokenManager = $tokenManager;
        $this->version      = $version;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    protected function log($level, $message)
    {
        if ($this->logger) {
            $this->logger->log($level, $message);
        }
    }

    public function create(string $objectType, array $parameters)
    {
        return $this->doRequest(new Request\Create($objectType, $parameters));
    }

    public function get(string $objectType, string $id, array $parameters = [])
    {
        return $this->doRequest(new Request\Get($objectType, $id, $parameters));
    }

    public function update(string $objectType, string $id, array $parameters)
    {
        return $this->doRequest(new Request\Update($objectType, $id, $parameters));
    }

    public function delete(string $objectType, string $id)
    {
        return $this->doRequest(new Request\Delete($objectType, $id));
    }

    public function query(string $queryString)
    {
        return $this->doRequest(new Request\Query($queryString));
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

            // salesforce update return an empty body
            if ($response->getContent() !== "") {
                return $response->toArray();
            }

            return [];
        } catch (\Exception $e) {
            $this->log('error', $e->getMessage());
            $this->log('error', print_r($response, true));
            throw $e;
        }
    }

    protected function sendRequest(TokenInterface $token, RequestInterface $request): ResponseInterface
    {
        $options = [
            'headers' => [
                'authorization' => sprintf('%s %s', $token->getTokenType(), $token->getAccessToken()),
            ],
        ];

        if ($request->hasBody()) {
            //$options['headers']['Content-type'] = 'application/json';
            $options['json'] = $request->getParams();
        }

        $response = $this->client->request(
            $request->getMethod(),
            $this->getUri($token, $request),
            $options
        );

        return $response;
    }

    protected function getUri(TokenInterface $token, RequestInterface $request): string
    {
        return sprintf(
            '%s/%s/%s/%s',
            $token->getInstanceUrl(),
            self::PREFIX,
            $this->version,
            $request->getEndpoint()
        );
    }
}

<?php

namespace Mcm\SalesforceClient;

use Mcm\SalesforceClient\Request\RequestInterface;
use Mcm\SalesforceClient\Response\SalesforceResponse;
use Mcm\SalesforceClient\Response\SalesforceResponseException;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

use Mcm\SalesforceClient\Request;
use Mcm\SalesforceClient\Security\Token\TokenInterface;
use Mcm\SalesforceClient\Security\Token\TokenGeneratorInterface;

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

    public function create(string $objectType, array $parameters): SalesforceResponse
    {
        return $this->doRequest(new Request\Create($objectType, $parameters));
    }

    public function get(string $objectType, string $id, array $parameters = []): SalesforceResponse
    {
        return $this->doRequest(new Request\Get($objectType, $id, $parameters));
    }

    public function update(string $objectType, string $id, array $parameters): SalesforceResponse
    {
        return $this->doRequest(new Request\Update($objectType, $id, $parameters));
    }

    public function delete(string $objectType, string $id): SalesforceResponse
    {
        return $this->doRequest(new Request\Delete($objectType, $id));
    }

    public function query(string $queryString): SalesforceResponse
    {
        return $this->doRequest(new Request\Query($queryString));
    }

    public function queryNext(string $queryString): SalesforceResponse
    {
        return $this->doRequest(new Request\QueryNext($queryString));
    }

    public function systemLimits(): SalesforceResponse
    {
        return $this->doRequest(new Request\System\Limits());
    }

    /**
     * @param RequestInterface $request
     *
     * @return SalesforceResponse
     *
     * @throws SalesforceResponseException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function doRequest(RequestInterface $request): SalesforceResponse
    {
        $token = $this->tokenManager->getToken();

        try {
            $response = null;
            $response = $this->sendRequest($token, $request);

            // Token is expired or invalid - get new and retry
            if ($response->getStatusCode() == Response::HTTP_UNAUTHORIZED) {
                $response = $this->sendRequest($this->tokenManager->regenerateToken($token), $request);
            }

            $salesforceResponse = new SalesforceResponse;
            $salesforceResponse->setHttpStatus($response->getStatusCode());

            if ($salesforceResponse->hasCriticalError()) {
                throw new SalesforceResponseException($salesforceResponse->getHttpStatusMessage());
            }

            // bypass http client exceptions if SalesforceResponse has no critical error
            if ($response->getContent(false) !== "") {
                $salesforceResponse->setContent($response->toArray(false));
            }

            $this->log('debug', 'SalesforceResponse : '.json_encode($salesforceResponse));

            return $salesforceResponse;

        } catch (\Exception $e) {
            $this->log('error', $e->getMessage());
            $this->log('debug', 'HTTP response : '.print_r($response, true));
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
            $options['json'] = $request->getParams();
        }

        $this->log(
            'debug',
            sprintf(
                'request method : %s uri : %s, options : %s',
                $request->getMethod(),
                $this->getUri($token, $request),
                json_encode($options)
            )
        );

        return $this->client->request(
            $request->getMethod(),
            $this->getUri($token, $request),
            $options
        );
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

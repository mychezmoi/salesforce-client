<?php

namespace Mcm\SalesforceClient\Client;

use Symfony\Component\HttpFoundation\Response;

class SalesforceResponse implements \JsonSerializable
{
    private ?int $httpStatusCode;
    private ?string $httpStatusMessage;
    private ?bool $httpUnhandledCode = false;
    private bool $httpCriticalCode = false;

    private ?array $content = [];

    public function jsonSerialize()
    {
        return [
            'HTTP Status Code' => $this->httpStatusCode,
            'HTTP Status Message' => $this->httpStatusMessage,
            'content' => $this->content
        ];
    }

    public function setHttpStatus(int $httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;

        switch ($httpStatusCode) {
            case Response::HTTP_OK:
                $this->httpStatusMessage = '“OK” success code, for GET or HEAD request.';
                break;
            case Response::HTTP_CREATED:
                $this->httpStatusMessage = '“Created” success code, for POST request.';
                break;
            case Response::HTTP_NO_CONTENT:
                $this->httpStatusMessage = '“No Content” success code, for PATH or DELETE request.';
                break;
            case Response::HTTP_MULTIPLE_CHOICES:
                $this->httpStatusMessage = 'The value returned when an external ID exists in more than one record.The response body contains the list of matching records.';
                break;
            case Response::HTTP_NOT_MODIFIED:
                $this->httpStatusMessage = 'The request content has not changed since a specified date and time.The date and time is provided in a if-Modified - Since header.See Get Object Metadata Changes for an example.';
                break;
            case Response::HTTP_BAD_REQUEST:
                $this->httpStatusMessage = 'The request could not be understood, usually because the JSON or XML body contains an error.';
                break;
            case Response::HTTP_UNAUTHORIZED:
                $this->httpStatusMessage = 'The session ID or OAuth token used has expired or is invalid.The response body contains the message and errorCode.';
                $this->httpCriticalCode  = true;
                break;
            case Response::HTTP_FORBIDDEN:
                $this->httpStatusMessage = 'The request has been refused.Verify that the logged - in user has appropriate permissions. if the error code is REQUEST_LIMIT_EXCEEDED, you’ve exceeded API request limits in your org.';
                $this->httpCriticalCode  = true;
                break;
            case Response::HTTP_NOT_FOUND:
                $this->httpStatusMessage = 'The requested resource could not be found.Check the URI for errors, and verify that there are no sharing issues.';
                break;
            case Response::HTTP_METHOD_NOT_ALLOWED:
                $this->httpStatusMessage = 'The method specified in the Request - Line isn’t allowed for the resource specified in the URI.';
                $this->httpCriticalCode  = true;
                break;
            case Response::HTTP_CONFLICT:
                $this->httpStatusMessage = 'The request could not be completed due to a conflict with the current state of the resource.Check that the API version is compatible with the resource you are requesting.';
                $this->httpCriticalCode  = true;
                break;
            case Response::HTTP_REQUEST_URI_TOO_LONG:
                $this->httpStatusMessage = 'The length of the URI exceeds the 16,384 byte limit.';
                $this->httpCriticalCode  = true;
                break;
            case Response::HTTP_UNSUPPORTED_MEDIA_TYPE:
                $this->httpStatusMessage = 'The entity in the request is in a format that’s not supported by the specified method.';
                $this->httpCriticalCode  = true;
                break;
            case Response::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE:
                $this->httpStatusMessage = 'The combined length of the URI and headers exceeds the 16,384 byte limit.';
                $this->httpCriticalCode  = true;
                break;
            case Response::HTTP_INTERNAL_SERVER_ERROR:
                $this->httpStatusMessage = 'An error has occurred within Lightning Platform, so the request could not be completed.Contact Salesforce Customer Support.';
                $this->httpCriticalCode  = true;
                break;
            default:
                $this->httpUnhandledCode = true;
        }
    }

    public function getHttpStatusCode(): ?int
    {
        return $this->httpStatusCode;
    }

    public function getHttpStatusMessage(): ?string
    {
        return !empty($this->httpStatusMessage) ? $this->httpStatusMessage : 'Unhandled HTTP CODE : '.$this->httpStatusCode;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): void
    {
        $this->content = $content;
    }

    public function hasCriticalError(): bool
    {
        return
            $this->httpStatusCode === null ||
            $this->httpUnhandledCode === true ||
            $this->httpCriticalCode === true;
    }

    public function hasError(): bool
    {
        return isset($this->content[0]) && isset($this->content[0]['errorCode']) === true;
    }

    public function getErrors(): array
    {
        return $this->hasError() ? $this->content[0] : [];
    }

    public function getErrorCode(): ?string
    {
        return $this->hasError() ? $this->content[0]['errorCode'] : null;
    }

    public function getErrorMessage(): ?string
    {
        return $this->hasError() ? $this->content[0]['message'] : null;
    }

    public function getErrorFields(): ?array
    {
        return $this->hasError() ? $this->content[0]['fields'] : [];
    }
}
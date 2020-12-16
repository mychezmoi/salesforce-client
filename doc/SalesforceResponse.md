SalesforceResponse
===

## Introduction

SalesforceClient make HTTP requests and return a SalesforceResponse or throws an exception.

It throws an Exception if a critical error occurs, some examples:
* an HTTP_INTERNAL_SERVER_ERROR
* an HTTP_REQUEST_URI_TOO_LONG
* a TransportException due to a loss of connection

If the error is due the content of the request, then it doesn't throw an Exception, the SalesforceResponse will have an error content.

Some examples :
* the object you look for does not exists (404)
* the object you try to create already exists (400)
* the object you try to delete does not exists (404)
* some flow or validations rules does not allow the object create/update/delete (400)

```php
try {
    $response = $salesforceClient->get(Contact::getSName(), 'non_existing_id');    
} catch (\Exception $e) {
    // critical exception, you may want to send an email to administrator
    
    $status = $response->getHttpStatusCode();
    $message = $response->getHttpStatusMessage();
    $content = $response->getContent();
}

if ($response->hasError()) {
    // handle the error, for example if the object is not found display a message to the end-user        
    if ($response->getHttpStatusCode() === 404) {

    }
}
```

[↑ Table of contents ↑](../README.md)
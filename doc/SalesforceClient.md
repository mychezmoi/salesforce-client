SalesforceClient
===
A basic client for managing objects in Salesforce.

### Setup

Extend the default client to add your credentials, you can change the default apcu token storage
> For more information on security tokens see “Reset Your Security Token” in the [online help](https://help.salesforce.com/articleView?id=user_security_token.htm&type=5).

```php
<?php

namespace MyApp\Service; // change to your app name

use Mcm\SalesforceClient\Client\SalesforceClient as McmSalesforceClient;
use Mcm\SalesforceClient\Generator\TokenGenerator;
use Mcm\SalesforceClient\Security\Authentication\Authenticator;
use Mcm\SalesforceClient\Security\Authentication\Credentials;
use Mcm\SalesforceClient\Security\Authentication\Strategy\PasswordGrantRegenerateStrategy;
use Mcm\SalesforceClient\Storage\ApcuTokenStorage;

class SalesforceClient extends McmSalesforceClient
{
    public function __construct(array $salesforceParameters)
    {
        $credentials = new Credentials(
            $salesforceParameters['id'], // Connected App -> Consumer Key
            $salesforceParameters['secret'], // Connected App -> Consumer Secret
            'password',
            [
                // account credentials
                'username' => $salesforceParameters['username'],
                // account password + security token
                'password' => $salesforceParameters['password'].$salesforceParameters['token'],
            ]
        );

        $authenticator = new Authenticator([new PasswordGrantRegenerateStrategy()]);

        $tokenGenerator = new TokenGenerator(
            $credentials,
            $authenticator,
            new ApcuTokenStorage() // you can change the storage
        );

        parent::__construct($tokenGenerator, $salesforceParameters['version']);
    }
}
```

### Define your model

For each salesforce object required you must define a php object

Example with Contact

```php
namespace MyApp\Model; // change to your app name

use Mcm\SalesforceClient\AbstractSObject;

class Contact extends AbstractSObject
{
    public static function getSName() : string
    {
        return 'Contact';
    }
}
```

## symfony 4+ configuration

.env.local

```ini
###> SALESFORCE ###
SALESFORCE_VERSION=v50.0
SALESFORCE_ID=myid
SALESFORCE_SECRET=mysecret
SALESFORCE_USERNAME=myuser@domain.com
SALESFORCE_PASSWORD=mypassword
SALESFORCE_TOKEN=mytoken
###< SALESFORCE ### 
```

config/services.yaml

```yaml
parameters:
    salesforce:
        version: '%env(SALESFORCE_VERSION)%'
        id: '%env(SALESFORCE_ID)%'
        secret: '%env(SALESFORCE_SECRET)%'
        username: '%env(SALESFORCE_USERNAME)%'
        password: '%env(SALESFORCE_PASSWORD)%'
        token: '%env(SALESFORCE_TOKEN)%'

services:    
    _defaults:
        autowire: true
        autoconfigure: true
        bind:
            $salesforceParameters: '%salesforce%'

    # optionnal logger always usefull
    MyApp\Service\SalesforceClient:  # change to your app name
        calls:
            - [ 'setLogger', [ '@logger' ] ]
```

### How to use

```php
use MyApp\Service\SalesforceClient; // change to your app name

use Myapp\Model\Contact; // change to your domain model

class MyAppClass 
{
    public function testSalesforceClient(SalesforceClient $salesforceClient)
    {
        // Create object
        $response  = $salesforceClient->create(
            Contact::getSName(),
            ['LastName' => 'New Contact created with SalesforceClient']
        );

        $contactId = $response->getContent()['id'];                                     

        // Get whole object
        $response  = $salesforceClient->get(Contact::getSName(), $contactId);        

        // Get only specified fields
        $response  = $salesforceClient->get(Contact::getSName(), $contactId, ['LastName']);

        // Update object
        $response  = $salesforceClient->update(Contact::getSName(), $contactId, ['LastName' => 'New name']);

        // Query
        $response  = $salesforceClient->query("SELECT LastName FROM ".Contact::getSName()." WHERE Id='".$contactId."'");

        // Delete object
        $response  = $salesforceClient->delete(Contact::getSName(), $contactId);        
    }
}
```
SalesforceClient
===
A basic client for managing objects in Salesforce.

## Setup

See [Install](Install.md)

## How to use

```php
use MyApp\Service\SalesforceClient; // change to your app name

use MyApp\Model\Contact; // change to your domain model

class MyAppClass
{
    public function testSalesforceClient(SalesforceClient $salesforceClient)
    {
        // Create object
        $response  = $salesforceClient->create(
            Contact::getSName(),
            ['LastName' => 'New Contact created with SalesforceClient']
        );

        $contact = new Contact;
        $contact->setSId($response->getContent()['id']);

        // Get whole object
        $response  = $salesforceClient->get(Contact::getSName(), $contact->getSId());

        // Once the content set you can acces it with the getters defined in Model\Contact
        $contact->setContent($response->getContent());

        // Get only specified fields
        $response  = $salesforceClient->get(Contact::getSName(), $contact->getSId(), ['LastName']);

        // Update object
        $response  = $salesforceClient->update(Contact::getSName(), $contact->getSId(), ['LastName' => 'New name']);

        // Query
        $response  = $salesforceClient->query("SELECT LastName FROM ".Contact::getSName()." WHERE ".Contact::getSIdField()."='".$contact->getSId()."'");

        // Delete object
        $response  = $salesforceClient->delete(Contact::getSName(), $contact->getSId());
    }
}
```

For more details about SalesforceResponse see [Salesforce Response](SalesforceResponse.md)

For more advanced query usage see [Query Building](QueryBuilding.md)

[↑ Table of contents ↑](../README.md)
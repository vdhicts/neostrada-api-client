# Neostrada API client for PHP examples

The API client always returns an array.

## Initialize the Neostrada API client.

```php
$client = Client::getInstance();
$client->setApiKey('[your_api_key]');
$client->setApiSecret('[your_api_secret]');
```

## Get domains

```php
$client->prepare('domains');
$client->execute();
$domainsResponse = $client->fetch();
```

## Get the top level domains (extensions)

```php
$client->prepare('extensions');
$client->execute();
$topLevelDomainsResponse = $client->fetch();
```

## Perform a whois

```php
$client->prepare('whois', [
	'domain'	=> 'my-domainname',
	'extension' => 'nl'
]);
$client->execute();
$client->fetch();
```

## Add a new holder

```php
$client->prepare('holder', [
	'holderid'		=> 0, // Set to holder's ID to update
	'sex'			=> 'M', // M for Male, V for Female
	'firstname'		=> 'Neostrada',
	'center'		=> '',
	'lastname'		=> 'BV',
	'street'		=> 'Vocweg',
	'housenumber'	=> '49',
	'hnpostfix'		=> '',
	'zipcode'		=> '8242KA',
	'city'			=> 'Lelystad',
	'country'		=> 'nl', // 2 letter country code
	'email'			=> 'domains@neostrada.nl'
]);
$client->execute();
$addHolderResponse = $client->fetch();
```

**Please note:**
Holders are only added if they do not exist yet. When adding an existing holder, the API will return the existing 
holder's ID.

## Delete a holder

```php
$client->prepare('deleteholder', [
	'holderid' => 1
]);
$client->execute();
$deleteHolderResponse = $client->fetch();
```

## Get holders

```php
$client->prepare('getholders', [
	'holderids'	=> '1,2,3' // Comma separated list with holder's IDs
]);
$client->execute();
$holdersResponse = $client->fetch();
```

**Please note:**
It is required to also include empty parameters in a request. For example, getholders has the optional `holderids` 
parameter. If you wish to retrieve ALL holders, create the request and set `holderids` to an empty string.

## Register a domain

```php
$client->prepare('register', [
	'domain'	=> 'neostrada',
	'extension'	=> 'nl',
	'holderid'	=> '[holderid]',
	'period'	=> 1,
	'webip'		=> '127.0.0.1', // leave this empty to use the Neostrada's default IP address
	'packageid'	=> 0 // optional package ID to add a Neostrada hosting package, contact Neostrada for the correct IDs
]);
$client->execute();
$registerResponse = $client->fetch();
```

## Register a domain with custom nameservers.

```php
$client->prepare('register2', [
	'domain'	=> 'neostrada',
	'extension'	=> 'nl',
	'holderid'	=> '[holderid]',
	'period'	=> 1,
	'packageid'	=> 0, // optional package ID to add a Neostrada hosting package, contact Neostrada for the correct IDs
	'webip'		=> '127.0.0.1',
	'ns1'		=> 'ns1.neostrada.nl',
	'ns2'		=> 'ns2.neostrada.nl',
	'ns3'		=> 'ns3.neostrada.nl'
]);
$client->execute();
$registerResponse = $client->fetch();
```

## Transfer a domain

```php
$client->prepare('transfer', [
	'domain'	=> 'neostrada',
	'extension' => 'nl',
	'authcode'	=> 'bleEEfrff!@'
]);
$client->execute();
$transferResponse = $client->fetch();
```

## Transfer a domain with custom nameservers

```php
$client->prepare('transfer2', [
	'domain'	=> 'neostrada',
	'extension' => 'nl',
	'authcode'	=> 'bleEEfrff!@',
	'holderid'	=> '[holderid]',
	'webip'		=> '',
	'ns1'		=> 'ns1.neostrada.nl',
	'ns2'		=> 'ns2.neostrada.nl',
	'ns3'		=> 'ns3.neostrada.nl'
]);
$client->execute();
$transferResponse = $client->fetch();
```

## Modify a domain

```php
$client->prepare('modify', [
	'domain'	=> 'neostrada',
	'extension' => 'nl',
	'holderid'	=> '[holderid]'
]);
$client->execute();
$modifyResponse = $client->fetch();
```

## Lock or unlock a domain

To unlock a domain, set lock to 0. To lock a domain, set lock to 1.

```php
$client->prepare('lock', [
	'domain'	=> 'neostrada',
	'extension' => 'com',
	'lock'		=> 1
]);
$client->execute();
$lockResponse = $client->fetch();
```

## Delete a domain

The domain subscription will be cancelled and will the domain will expire on the expiration date.

```php
$client->prepare('delete', [
	'domain'	=> 'neostrada',
	'extension' => 'nl'
]);
$client->execute();
$deleteResponse = $client->fetch();
```

## Get the auth token for the domain

```php
$client->prepare('gettoken', [
	'domain'	=> 'neostrada',
	'extension' => 'nl'
]);
$client->execute();
$authTokenResponse = $client->fetch();
```

## Get the expiration date for the domain

```php
$client->prepare('getexpirationdate', [
	'domain'	=> 'neostrada',
	'extension' => 'nl'
]);
$client->execute();
$expirationDateResponse = $client->fetch();
```

## Get the domains nameservers

```php
$client->prepare('getnameserver', [
	'domain'	=> 'neostrada',
	'extension' => 'nl'
]);
$client->execute();
$nameserversResponse = $client->fetch();
```

## Set the domains nameservers

```php
$client->prepare('nameserver', [
	'domain'	=> 'neostrada',
	'extension' => 'nl',
	'ns1'		=> 'ns1.neostrada.nl',
	'ns2'		=> 'ns2.neostrada.nl',
	'ns3'		=> 'ns3.neostrada.nl' // optional
]);
$client->execute();
$setNameserversResponse = $client->fetch();
```

## Get the dns for the domain

Returns an item for every DNS row. The item's value is a ; separated string with the following fields: 

`dnsrowid;name;type;content;timetolive;priority`
 
```php
$client->prepare('getdns', [
	'domain'	=> 'neostrada',
	'extension' => 'nl'
]);
$client->execute();
$dnsResponse = $client->fetch();
```

## Set dns for the domain

All data must be provided as an serialized array. Every item must be a sub array with the following format: 

```php
dnsrowid => array(
	'name' => '', 
	'type' => '', 
	'content => '', 
	'ttl' => '', 
	'prio' => ''
)
```

```php
$client->prepare('dns', [
	'domain'	=> 'neostrada',
	'extension' => 'nl',
	'dnsdata'	=> serialize([
		1 => [
			'name'		=> 'neostrada.nl',
			'type'		=> 'TXT',
			'content'	=> 'TEST DNS RECORD',
			'ttl'		=> 3600,
			'prio'		=> 0
        ]
    ])
]);
$client->execute();
$updateDnsResponse = $client->fetch();
```

**Please note:**
- Non existing records will not be added! You must use `adddns` for that.
- The SOA record will be updated automatically

## Add a dns record.

```php
$client->prepare('adddns', [
	'domain'	=> 'neostrada',
	'extension' => 'nl',
	'name'		=> 'neostrada.nl',
	'type'		=> 'TXT',
	'content'	=> 'TEST DNS RECORD',
	'prio'		=> 0, // E.x. 10 for first MX
	'ttl'		=> 3600
]);
$client->execute();
$addDnsResponse = $client->fetch();
```

**Please note:**
- The SOA record will be updated automatically

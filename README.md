# Neostrada API client for PHP

Easily connect your own system to the Neostrada API using the Neostrada API client and your 
[API credentials](https://www.neostrada.nl/mijn-account/api.html) to automatically register and manage 
domainnames. This package is based on the original 
[Neostrada API client](https://github.com/neostrada/neostrada-api-php) but turned into a composer package and some code 
is changed.

## Requirements

This package requires PHP 5.6 and uses cUrl.

## Installation

This package can be used in any PHP project or with any framework. The packages is tested in PHP 5.6 and 7.0.

You can install the package via composer:

```
composer require vdhicts/neostrada-api-client
```

## Usage

Please refer to the [examples](examples.md) for several examples for using this client and the Neostrada API. If you 
just need a quick start, use this:

```php
// Start the client
$client = \Vdhicts\Neostrada\Client::getInstance();

// Enter your API key and secret
$client->setApiKey('[your_api_key]');
$client->setApiSecret('[your_api_secret]');

// Prepare the request with the action and its parameters
$client->prepare('domains');

// Perform the request
$xml = $client->execute();

// Turn the response into an array
$domainsResponse = $client->fetch();
```

**Please note:**
- Every API call must be signed, the client will do this automatically. The API secret is used for this signature.
- It is required to also include empty parameters in a request. For example, the `getholders` request has the optional 
`holderids` parameter. If you wish to retrieve all holders, create the request and set `holderids` to an empty string.

## Contribution

Any contribution is welcome, but it should meet the PSR-2 standard and please create one pull request per feature. In 
exchange you will be credited as contributor on this page.

## License

This package is based on the original 
[Neostrada API client](https://github.com/neostrada/neostrada-api-php) and therefor distributed under the 
[BSD (Berkeley Software Distribution) License](http://www.opensource.org/licenses/bsd-license.php).

## Support

This package isn't an official package from Neostrada, so they probably won't offer support for it. If you encounter a 
problem with this client, please open an issue on GitHub.

# See

- [Neostrada](https://www.neostrada.nl)

# getAddress PHP SDK [![CircleCI](https://circleci.com/gh/philcross/getaddress-php/tree/master.svg?style=svg)](https://circleci.com/gh/philcross/getaddress-php/tree/master)

This is a framework agnostic PHP SDK for getAddress.io's address lookup API.

To use this package, you will need an API key provided by getAddress.io, the basic package is free. When you sign up, you will be given both a API key for accessing public address information, and an administrative API key for managing your account.

This package also relies on PHP 5.6

## Installation

Installation is easily done via composer

```
composer require philcross/getaddress-php
```

## Basic Usage

To begin, you will need both of your API keys provided by getAddress.io, then create the client:

```
$client = new Philcross\GetAddress\Client($apiKey, $administrativeApiKey);
```

There are a bunch of helpful methods on the client which will allow you to lookup addresses, and manage your account. The client will know which API key to use.

## Looking up a public address

To lookup a public address you will need, at minimum, a postcode. You can also specify a property number, but this is optional.
The find method will return an `AddressResponse` object. This contains an array of `Address` objects.

```
$response = $client->find($postcode, $propertyNumber);

$longitude = $response->getLongitude();
$latitude  = $response->getLatitude();

foreach ($response->getAddresses() as $address) {
    // ...
}
```

You can also supply a 3rd boolean argument to the `find()` method. By default, it will requests are returned by getAddress.io sorted numerically. If you don't want them sorted numerically, you can provide boolean false:

```
$response = $client->find('TQ2 6TP', null, false);

// Or if you would prefer to use a named constant to make it easier to read

$response = $client->find('TQ2 6TP', null, Philcross\GetAddress\Responses\Address::SORT_NUMERICALLY);
```

### Using the Address Object

The address object has a few helpful methods on it:

```
// To retrieve individual address elements
$line1 = $address->getLine1();
$line2 = $address->getLine2();
$line3 = $address->getLine3();
$line4 = $address->getLine4();
$locality = $address->getLocality();
$city = $address->getCity();
$county = $address->getCounty();

$line2 = $address->getLine(2);
```

There is also a `getTown()` method, but this is an alias of `getCity()`:

```
$address->getTown(); // Torquay
$address->getCity(); // Torquay
```

To return the address as an associative array, you can use the `toArray()` method:

```
$address->toArray();

// Outputs:

[
    'line_1'    => 'Sample Line 1',
    'line_2'    => 'Sample Line 2',
    'line_3'    => 'Sample Line 3',
    'line_4'    => 'Sample Line 4',
    'locality'  => 'Sample Locality',
    'town_city' => 'Sample City',
    'county'    => 'Sample County',
]
```

In addition, you can overwrite the keys of the array by providing an array to the `toArray()` method:

```
$address->toArray(['house_name', 'street_address']);

// Outputs:

[
    'house_name'     => 'Sample Line 1',
    'street_address' => 'Sample Line 2',
    'line_3'         => 'Sample Line 3',
    'line_4'         => 'Sample Line 4',
    'locality'       => 'Sample Locality',
    'town_city'      => 'Sample City',
    'county'         => 'Sample County',
]
```

You can also convert the address to a comma seperate list of elements using the `toString()` method.

```
echo $address->toString();

// You can also cast the object to a string:
echo $address;

Sample Line 1,Sample Line 2,,,Sample Locality,Sample City,Sample County
```

by default, the `toString()` method will not take out empty elements. This is useful to conver the address to a CSV record.
If you want to format the address as a nice string, removing the empty elements:

```
echo $address->toString(true);

Sample Line 1,Sample Line 2,Sample Locality,Sample City,Sample County
```

One last helper is the `sameAs()` method, which accepts another `Address` object to compare. This will compare the address arrays to see if they're the same:

```
$address->sameAs($address2); // Returns a boolean
```

## Checking your account usage

You can quickly check your account usage using the `usage()` method on the client.

If you don't provide any arguments, it will return the current usage for the day. The usage method will return a `Usage` object:

```
$usage = $client->getUsage();

$usage->getCount();
$usage->getLimit1();
$usage->getLimit2();

// Additional methods for retrieving limits:

$usage->getLimit(1);
$usage->getLimits(); // Returns an array
```

There are also a few extra helper methods. To check how many requests you have remaining, until you run out for the day:

```
$usage->requestsRemaining();
```

You can also supply boolean true to the method to return the number of requests remaining until getAddress.io slows it's response down:

```
$usage->requestsRemaining(true);

// Or of you prefer a more natural language

$usage->requestsRemainingUntilSlowed();
```

You can also check to see if you've exceeded your limit:

```
$usage->hasExceededLimit();
```

And one last one, you can check if you've made enough requests for getAddress.io to slow the responses down, but not enough to for them to stop sending responses.

```
$usage->isRestricted();
```

If you want to retrieve your usage from a previous date, you can supply a DateTime object (or a Carbon instance) to the usage method:

```
$datetime = new DateTime('2017-08-24');

// or...

$datetime = Carbon\Carbon::now();

$client->usage($datetime);
```

Alternatively, you can supply the day month and year as parameters to the method:

```
$client->usage(24, 08, 2017); // $day, $month, $year
```

## Private Addresses

getAddress.io allows you to provide private addresses. These are addresses you supply, that are only available by using your API token.

### Adding a new private address

You can add a new private address:

```
$address = Philcross\GetAddress\Responses\PrivateAddress::create(
    'line 1',
    'line 2',
    'line 3',
    'line 4',
    'locality',
    'city',
    'county'
);

$response = $client->addPrivateAddress('TQ2 6TP', $address);
```

When you create a new private address, it will return a `PrivateAddressResponse` object, which contains a message provided by getAddress, and the new address you created as a `PrivateAddress` object:

```
$response->getMessage(); // Returns a string
$response->getAddresses(); // Returns a collection of PrivateAddress objects
```

The `PrivateAddress` object extends the `Address` object, so you can still use methods like `sameAs()` etc.
The `PrivateAddress` object also contains a `getAddressId()` method, to return the ID of your private address record.

### Deleting a private address

If you have the ID and postcode of a private address, you can delete it:

```
$client->deletePrivateAddress('TQ26TP', 1);
```

Again, this will return a `PrivateAddressResponse` object with a message.

### Listing private addresses

You can list all private addresses you have for a specified postcode:

```
$response = $this->getPrivateAddress('TQ2 6TP');

$response->getAddresses(); // Returns an array
```

Or, if you know the ID of the property, you can supply the ID as well:

```
$response = $this->getPrivateAddress('TQ2 6TP', 1);

$response->getAddresses();
```

## Security

getAddress allow you to whitelist domains and IP addresses.

### Adding a domain or IP address to the whitelist

```
$client->addDomainToWhitelist('google.com');
$client->addIpToWhitelist('8.8.8.8');
```

Both methods return a `WhitelistResponse` object, which have `getMessage()` and `getItems()` methods.

```
$response = $client->addDomainToWhitelist('google.com');

$response->getMessage();
$response->getItems(); // Returns an array
```

The `getItems()` method will always return an array full of either `Domain` objects, or `Ip` objects, but never a combination.

Both `Domain` and `Ip` objects have a `getId()` method to return the unique ID for that object.
The `Domain` object contains a `getDomain()` method, while the `Ip` object contains a `getIp()` method.

### Deleting an existing whitelisted item

```
$response = $client->deleteDomainFromWhitelist($id);
$response = $client->deleteIpFromWhitelist($id);
```

Both methods will return a `WhitelistResponse` object.

### Listing whitelisted domains or IP addresses

```
$response = $client->getWhitelistedDomains();
$response = $client->getWhitelistedIpAddresses();
```

Each method also has a related singular version of the method name if you know the ID of the whitelisted item.

```
$response = $client->getWhitelistedDomain($id);
$response = $client->getWhitelistedIpAddress($id);
```

## Webhooks

getAddress allows you to specify urls to use as webhooks when you reach your first limit.

### Adding a new URL as a webhook

```
$response = $client->addWebhook('yoursite.com/webhooks/first-limit-reached');
```

This will return a `WebhookResponse` object which contains a `getMessage()` method, and `getHooks()` method which returns an array of `Webhook` objects.

Each `Webhook` object contains a `getWebhookId()` method, and `getWebhookUrl()` method.

### Deleting an existing hook

To delete an existing webhook, you need to provide the ID:

```
$response = $client->deleteWebhook($id);
```

### Listing all webhooks

You can list all webhooks using this method. It will return a `WebhookResponse` object like above.

```
$response = $client->getWebhooks();
```

Alternatively, you can use the singular version of the method to retrieve a single webhook by it's ID:

```
$response = $client->getWebhook($id);
```

## Handling errors

If there is an error returned from getAddress.io, an exception will be thrown specific to the error returned.

| Status | Meaning                        | Exception                                                 |
|--------|--------------------------------|-----------------------------------------------------------|
| 404    | The postcode couldn't be found | Philcross\GetAddress\Exceptions\PostcodeNotFoundException |
| 401    | Invalid API key                | Philcross\GetAddress\Exceptions\ForbiddenException        |
| 400    | Invalid Postcode               | Philcross\GetAddress\Exceptions\InvalidPostcodeException  |
| 429    | To many API requests           | Philcross\GetAddress\Exceptions\TooManyRequestsException  |
| 500    | getAddress server exception    | Philcross\GetAddress\Exceptions\ServerException           |

For any other 4xx or 5xx error, an `Philcross\GetAddress\Exceptions\UnknownException` exception wil be thrown instead.

## Tests

This package comes with a set of php unit tests. To run them:

```
./vendor/bin/phpunit
```

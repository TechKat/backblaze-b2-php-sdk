
# Backblaze B2 SDK for PHP

Backblaze B2 is a super low-cost hot cloud storage solution with the lowest price-per-GB on the market (to date of this README's last change). This SDK is a library for PHP developers to setup a quick and easy link between their applications and BackBlaze B2 API with all the hard coding done for them.

This SDK is one of many PHP packages, all with the same purpose of providing easy to use access to the BackBlaze B2 API, and adds to a variety of options to the pool to choose from.

By default, the package will use the `v2` version of the BackBlaze B2 API, but `v1` can be specified as demonstrated in an example below.

---

# Quickstart

You will first need to generate an application key on the BackBlaze website by [logging in](https://secure.backblaze.com/user_signin.htm) and navigating to Account > Application Keys. Click on the "Add a New Application Key" button and fill in the form as required for your application. Make a note of the Key ID and Application Key.

**It is not recommended to use the master key ID or master Application Key.**

```php
<?php

use TechKat\BackBlazeB2\Client;

$keyID = '<insert the key ID here>';
$applicationKey = '<insert the application key here>';

$options = array(
    // Time in seconds on how long a BackBlaze B2 authorization token should remain in cache.
    'authTimeout' => 43200,

    // Which version of the BackBlaze B2 API you should use. It is best to keep as 2.
    'version' => 2,

    // If the BackBlaze B2 authorization token needs to be recycled on runtime, set this to true.
    // Keep in mind that keeping this option to true always will incur additional class C transactions on BackBlaze B2's API.
    'forceReauthorization' => false,
);

$client = new Client($keyID, $applicationKey, $options);

// This returns a list of Bucket models per BackBlaze B2 bucket that the key ID and application key has access to.
$buckets = $client->listBuckets();

foreach($buckets as $bucket)
{
    echo 'Bucket Name: ' . $bucket->getName();
}
```
---
# Installation

The package is available via composer:

```bash
composer require techkat/backblaze-b2-php-sdk
```

# Contributors

Please feel free to contribute in any way that might benefit anyone that uses this package, by adding suggestions or making a pull request.

# License

MIT

# Documentation

Please refer to the [wiki](https://github.com/TechKat/backblaze-b2-php-sdk/wiki/Introduction) for more detailed explanations of how to use the library.

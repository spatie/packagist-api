# Fetch package info from Packagist

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/packagist-api.svg?style=flat-square)](https://packagist.org/packages/spatie/packagist-api)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/packagist-api/master.svg?style=flat-square)](https://travis-ci.org/spatie/packagist-api)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/packagist-api.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/packagist-api)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/packagist-api.svg?style=flat-square)](https://packagist.org/packages/spatie/packagist-api)

This package makes it easy to search and fetch package info using [the Packagist API](https://packagist.org/apidoc).

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

All postcards are published [on our website](https://spatie.be/en/opensource/postcards).

## Installation

You can install the package via composer:

``` bash
composer require spatie/packagist-api
```

## Usage

You must pass a Guzzle client to the constructor of `Spatie\Packagist\Packagist`.

``` php
$client = new \GuzzleHttp\Client();

$packagist = new \Spatie\Packagist\Packagist($client);
```

### Get all packages by a specific vendor
``` php
$packagist->getPackagesByVendor('spatie');
```

### Find a package by it's name
``` php
$packagist->findPackageByName('spatie/laravel-backup');
```

### Get all packages by type
``` php
$packagist->getPackagesByType('symfony-bundle');
```

### Get all packages by name and type
``` php
$packagist->getPackagesByName('monolog', 'symfony-bundle')
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [Jolita Grazyte](https://github.com/JolitaGrazyte)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

# Fetch package info from Packagist

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/packagist-api.svg?style=flat-square)](https://packagist.org/packages/spatie/packagist-api)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/packagist-api/master.svg?style=flat-square)](https://travis-ci.org/spatie/packagist-api)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/525f7751-3455-4b59-b607-42f69abf5a7b.svg?style=flat-square)](https://insight.sensiolabs.com/projects/525f7751-3455-4b59-b607-42f69abf5a7b)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/packagist-api.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/packagist-api)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/packagist-api.svg?style=flat-square)](https://packagist.org/packages/spatie/packagist-api)

This package makes it easy to search and fetch package info using [the Packagist API](https://packagist.org/apidoc).

## Installation

You can install the package via composer:

``` bash
composer require spatie/packagist-api
```

## Usage

``` php
$client = new GuzzleHttp\Client();

$packagist = new Spatie\Packagist\Packagist($client);
```

### Get all packages by a specific vendor
``` php
$packagist->getVendorPackages('spatie');
```

### Find a package by it's name
``` php
$packagistApi->getPackageByName('spatie/laravel-backup');
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

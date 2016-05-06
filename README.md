# Packagist-API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/packagist-api.svg?style=flat-square)](https://packagist.org/packages/spatie/packagist-api)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/packagist-api/master.svg?style=flat-square)](https://travis-ci.org/spatie/packagist-api)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/xxxxxxxxx.svg?style=flat-square)](https://insight.sensiolabs.com/projects/xxxxxxxxx)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/packagist-api.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/packagist-api)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/packagist-api.svg?style=flat-square)](https://packagist.org/packages/spatie/packagist-api)

This package communicates with the Packagist Api and makes it easy to search and receive packages information.
It has 4 simple methods that you can call with one line of code:  `getVendorPackages(string $vendor)`, `getPackageByName(string $vendor, string $package)`, 
`searchPackagesByTag(string $tag)` and `searchPackagesByName(string $name)`.

## Installation

You can install the package via composer:

``` bash
composer require spatie/packagist-api
```

## Usage

### Make a new PackagistApi instance.
First of all you must make a new instance of PackagistApi. 
Therefore you will also need an instance of GuzzelHttp Client.

``` php
$client = new GuzzleHttp\Client();
$packagistApi = new PackagistApi($client);
```

### Get a list of packages 
``` php
// get a list of vendor packages where vendor name is 'spatie'
$packagistApi->getVendorPackages('spatie');
```

### Get a specific package
``` php
// get a package where vendor name is 'spatie' and package name 'valuestore'
$packagistApi->getPackageByName('spatie', 'valuestore');
```

### Search packages by tag
``` php
// search for packages where tag is '1.0.0'
$packagistApi->searchPackagesByTag('1.0.0');
```

### Search packages by name
``` php
// search for a package with a name 'valuestore'
$packagistApi->searchPackagesByName('valuestore');
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

- [Jolita Grazyte](https://github.com/JolitaGrazyte)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

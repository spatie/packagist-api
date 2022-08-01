
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Fetch package info from Packagist

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/packagist-api.svg?style=flat-square)](https://packagist.org/packages/spatie/packagist-api)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/packagist-api/run-tests?label=tests)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/packagist-api.svg?style=flat-square)](https://packagist.org/packages/spatie/packagist-api)

This package makes it easy to search and fetch package info using [the Packagist API](https://packagist.org/apidoc).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/packagist-api.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/packagist-api)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

``` bash
composer require spatie/packagist-api
```

There is also a [Laravel wrapper](https://packagist.org/packages/markwalet/laravel-packagist) available for this package.
## Usage

You must pass a Guzzle client and a url generator to the constructor of `Spatie\Packagist\PackagistClient`.

```php
$client = new \GuzzleHttp\Client();
$generator = new \Spatie\Packagist\PackagistUrlGenerator();

$packagist = new \Spatie\Packagist\PackagistClient($client, $generator);
```

### List package names
```php
// All packages
$packagist->getPackagesNames();

// List packages by type.
$packagist->getPackagesNamesByType('composer-plugin');

// List packages by organization
$packagist->getPackagesNamesByVendor('spatie');
```

### Searching for packages
```php
// Search packages by name.
$packagist->searchPackagesByName('packagist');

// Search packages by tag.
$packagist->searchPackagesByTags('psr-3');

// Search packages by type.
$packagist->searchPackagesByType('composer-plugin');

// Combined search.
$packagist->searchPackages('packagist', ['type' => 'library']);
```

#### Pagination
Searching for packages returns a paginated result. You can change the pagination settings by adding more parameters.

```php
// Get the third page, 10 items per page.
$packagist->searchPackagesByName('packagist', 3, 10);
```

### Getting package data.
```php
// Using the Composer metadata. (faster, but less data)
$packagist->getPackageMetadata('spatie/packagist-api');
$packagist->getPackageMetadata('spatie', 'packagist-api');

// Using the API. (slower, cached for 12 hours by Packagist.
$packagist->getPackage('spatie/packagist-api');
$packagist->getPackage('spatie', 'packagist-api');
```

### Get Statistics
```php
$packagist->getStatistics();
```

### Get security vulnerability advisories
```php
// Get advisories for specific packages
$packages = ['spatie/packagist-api'];
$advisories = $packagist->getAdvisories($packages);

// Get advisories for specific packages that were updated after some timestamp
$packages = ['spatie/packagist-api'];
$advisories = $packagist->getAdvisories($packages, strtotime('2 weeks ago'));

// Get advisories only for specific versions of specific packages
$packages = ['spatie/packagist-api' => '2.0.2'];
$advisories = $packagist->getAdvisoriesAffectingVersions($packages);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [Jolita Grazyte](https://github.com/JolitaGrazyte)
- [Mark Walet](https://github.com/markwalet)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

# Changelog

All notable changes to `packagist-api` will be documented in this file

## 2.0.2 - 2020-12-01

- add support for PHP 8 ([#19](https://github.com/spatie/packagist-api/pull/19))

## 2.0.1 - 2020-09-10

- allow Guzzle 7

## 2.0.0 - 2020-04-08

- Renamed `Packagist` class to `PackagistClient`.
- Changed the interface of the `PackagistClient` class to reflect the current state of the [Packagist API](https://packagist.org/apidoc)
- Moved url generation to a separate class.
- Moved vendor formatting to a separate class.
- Changed the meta data method to actually use the repository endpoint.
- Split up integration and unit tests

## 1.3.1 - 2020-03-25

- add support for PHP 7.2

## 1.3.0 - 2020-03-25

- Dropped support for anything below PHP 7.3

## 1.2.1 - 2019-04-10

- throw error in find package name if string doesn't contain '/'

## 1.2.0 - 2018-08-29

- add ability to search by both package and type

## 1.1.0 - 2018-05-08

- add `findPackageByType`

## 1.0.1 - 2017-06-13

- throw an exception when passing an empty value to `getPackagesByVendor`

## 1.0.0 - 2016-05-14

- initial release

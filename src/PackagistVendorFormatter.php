<?php

namespace Spatie\Packagist;

use Spatie\Packagist\Exceptions\InvalidArgumentException;

class PackagistVendorFormatter
{
    public static function format(string $vendor, ?string $package = null): array
    {
        if (empty($package) === false) {
            return [$vendor, $package];
        }

        if (strpos($vendor, '/') === false) {
            throw new InvalidArgumentException('The vendor argument should contain a `/`.');
        }

        return explode('/', $vendor, 2);
    }
}

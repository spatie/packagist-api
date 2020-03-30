<?php

namespace Spatie\Packagist;

use Spatie\Packagist\Exceptions\InvalidArgumentException;

class PackagistVendorFormatter
{
    /**
     * Format the given vendor and package to an array.
     *
     * @param string      $vendor
     * @param string|null $package
     *
     * @return array|string[] A 2-item list ([$vendor, $package])
     */
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

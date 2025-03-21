<?php

namespace Spatie\Packagist;

use Spatie\Packagist\Exceptions\InvalidArgumentException;

class PackagistVendorFormatter
{
    public static function format(string $vendor, ?string $package = null): array
    {
        if (($package === null || $package === '' || $package === '0') === false) {
            return [$vendor, $package];
        }

        if (! str_contains($vendor, '/')) {
            throw new InvalidArgumentException('The vendor argument should contain a `/`.');
        }

        return explode('/', $vendor, 2);
    }
}

<?php

namespace Spatie\Packagist\Test\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Spatie\Packagist\Exceptions\InvalidArgumentException;
use Spatie\Packagist\PackagistVendorFormatter;

class PackagistVendorFormatterTest extends TestCase
{
    #[Test]
    public function it_does_nothing_when_a_package_name_is_given()
    {
        [$vendor, $package] = PackagistVendorFormatter::format('spatie', 'packagist-api');

        $this->assertEquals('spatie', $vendor);
        $this->assertEquals('packagist-api', $package);
    }

    #[Test]
    public function it_splits_up_the_vendor_when_a_package_is_omitted()
    {
        [$vendor, $package] = PackagistVendorFormatter::format('spatie/packagist-api');

        $this->assertEquals('spatie', $vendor);
        $this->assertEquals('packagist-api', $package);
    }

    #[Test]
    public function it_throws_an_exception_when_the_vendor_cannot_be_split_up()
    {
        $this->expectException(InvalidArgumentException::class);

        PackagistVendorFormatter::format('spatie');
    }
}

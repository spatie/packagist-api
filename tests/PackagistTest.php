<?php

namespace Spatie\Packagist\Test;

use GuzzleHttp\Client;
use Spatie\Packagist\Packagist;

class PackagistTest extends \PHPUnit_Framework_TestCase
{
    /** @var PackagistApi */
    protected $packagist;

    public function setUp()
    {
        $client = new Client();

        $this->packagist = new Packagist($client);

        parent::setUp();
    }

    /** @test */
    public function it_can_get_all_the_packages_of_a_specific_vendor()
    {
        $vendorName = 'spatie';

        $result = $this->packagist->getPackagesByVendor($vendorName);

        $this->assertArrayHasKey('packageNames', $result);

        $this->assertGreaterThan(0, count($result['packageNames']));

        foreach ($result['packageNames'] as $packageName) {
            $this->assertStringStartsWith($vendorName, $packageName);
        }
    }

    /** @test */
    public function it_can_find_a_package_by_its_name()
    {
        $packageName = 'spatie/laravel-medialibrary';

        $result = $this->packagist->findPackageByName('spatie/laravel-medialibrary');

        $this->assertArrayHasKey('package', $result);

        $this->assertSame($packageName, $result['package']['name']);
    }
}

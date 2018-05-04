<?php

namespace Spatie\Packagist\Test;

use Exception;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Spatie\Packagist\Packagist;

class PackagistTest extends TestCase
{
    /** @var \Spatie\Packagist\Packagist */
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
    public function it_will_throw_an_exception_if_a_vendor_is_not_specified()
    {
        $this->setExpectedException(Exception::class);

        $this->packagist->getPackagesByVendor('');
    }

    /** @test */
    public function it_can_find_a_package_by_its_name()
    {
        $vendor = 'spatie';
        $packageName = 'menu';

        $result = $this->packagist->findPackageByName($vendor, $packageName);

        $this->assertArrayHasKey('package', $result);

        $this->assertSame("{$vendor}/{$packageName}", $result['package']['name']);
    }

    /** @test */
    public function it_can_find_a_package_by_its_fully_qualified_name()
    {
        $fullPackageName = 'spatie/menu';

        $result = $this->packagist->findPackageByName($fullPackageName);

        $this->assertArrayHasKey('package', $result);

        $this->assertSame($fullPackageName, $result['package']['name']);
    }

    /** @test */
    public function it_can_get_packages_by_name()
    {
        $fullPackageName = 'spatie/menu';

        $result = $this->packagist->getPackagesByName($fullPackageName);

        $this->assertArrayHasKey('results', $result);

        $this->assertSame($fullPackageName, $result['results'][0]['name']);
    }
}

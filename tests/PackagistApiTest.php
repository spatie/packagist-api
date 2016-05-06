<?php

namespace Spatie\PackagistApi\Test;

use GuzzleHttp\Client;
use Spatie\PackagistApi\PackagistApi;
use Mockery;

class PackagistApiTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Mockery */
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = Mockery::mock(Client::class);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    public function it_can_search_packages_by_name()
    {
        $packagesName = 'spatie/url-signer';

        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->once()
            ->andReturn(json_encode(['results' => ['name' => $packagesName]]));

        $packagistApi = new PackagistApi($this->client);
        $output = $packagistApi->searchPackagesByName('url-signer')->results->name;

        $this->assertEquals($output, $packagesName);
    }

    /** @test */
    public function it_can_search_packages_by_tag()
    {
        $packagesName = 'spatie/valuestore';

        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->once()
            ->andReturn(json_encode(['results' => ['name' => $packagesName]]));

        $packagistApi = new PackagistApi($this->client);
        $output = $packagistApi->searchPackagesByTag('1.0.0')->results->name;

        $this->assertEquals($output, $packagesName);
    }

    /** @test */
    public function it_can_get_vendor_packages()
    {
        $packagesNames = ['spatie/7to5', 'spatie/activitylog'];

        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->once()
            ->andReturn(json_encode(['packageNames' => $packagesNames]));

        $packagistApi = new PackagistApi($this->client);
        $output = $packagistApi->getVendorPackages('spatie')->packageNames;

        $this->assertEquals($output, $packagesNames);
    }

    /** @test */
    public function it_get_package_by_name()
    {
        $packagesName = 'spatie/valuestore';

        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->once()
            ->andReturn(json_encode(['package' => ['name' => $packagesName]]));

        $packagistApi = new PackagistApi($this->client);
        $output = $packagistApi->getPackageByName('spatie', 'valuestore')->package->name;

        $this->assertEquals($output, $packagesName);
    }
}

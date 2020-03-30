<?php

namespace Spatie\Packagist\Test\Integration;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Spatie\Packagist\PackagistClient;
use Spatie\Packagist\PackagistUrlGenerator;

class PackagistClientTest extends TestCase
{
    /** @test */
    public function it_can_list_package_names()
    {
        $client = $this->client();

        $result = $client->getPackagesNames();

        $this->assertArrayHasKey('packageNames', $result);
    }

    /** @test */
    public function it_can_list_package_names_for_a_vendor()
    {
        $client = $this->client();

        $result = $client->getPackagesNamesByVendor('spatie');

        // Testing on the first 10 packages should be enough.
        $packages = array_slice($result['packageNames'], 0, 10);

        foreach ($packages as $package) {
            $this->assertStringStartsWith('spatie/', $package);
        }
    }

    /** @test */
    public function it_can_list_package_names_for_a_type()
    {
        $client = $this->client();

        // Do tests on the first found repository.
        $result = $client->getPackagesNamesByType('composer-plugin');
        $repository = current($result['packageNames']);

        $metadata = $client->getPackageMetadata($repository);

        // Get the latest version of the package.
        $latest = end($metadata['packages'][$repository]);

        // Test if the searched type is the type in the current repository.
        $this->assertEquals('composer-plugin', $latest['type']);
    }

    /** @test */
    public function it_can_search_packages_by_name()
    {
        $client = $this->client();

        $result = $client->searchPackagesByName('monolog');

        // We cannot do assertions on the list of repositories that is returned because
        // it is unclear how the search algorithm of Packagist works when searching on name.
        $this->assertArrayHasKey('results', $result);
        $this->assertIsArray($result['results']);
    }

    /** @test */
    public function it_can_set_the_page_size_when_searching()
    {
        $client = $this->client();

        $result = $client->searchPackages('monolog', [], 1, 2);

        $this->assertArrayHasKey('results', $result);
        $this->assertCount(2, $result['results']);
    }

    /** @test */
    public function it_can_search_packages_by_type()
    {
        $client = $this->client();

        // Do tests on the first found repository.
        $result = $client->searchPackagesByType('symfony-bundle');
        $repository = current($result['results']);

        $name = $repository['name'];
        $metadata = $client->getPackageMetadata($name);

        // Get the latest version of the package.
        $latest = end($metadata['packages'][$name]);

        // Test if the searched type is the type in the current repository.
        $this->assertEquals('symfony-bundle', $latest['type']);
    }

    /** @test */
    public function it_can_search_packages_by_tags()
    {
        $client = $this->client();

        // Do tests on the first found repository.
        $result = $client->searchPackagesByTags('psr-7');
        $repository = current($result['results']);

        $this->assertArrayHasKey('name', $repository);

        $name = $repository['name'];
        $metadata = $client->getPackageMetadata($name);

        // Get the latest version of the package.
        $latest = end($metadata['packages'][$name]);

        // Test if the searched tag is a keyword in the current repository.
        $this->assertContains('psr-7', $latest['keywords']);
    }

    /** @test */
    public function it_can_get_a_package_via_the_api()
    {
        $client = $this->client();

        $result = $client->getPackage('spatie', 'packagist-api');

        $this->assertArrayHasKey('package', $result);
        $this->assertArrayHasKey('name', $result['package']);
        $this->assertEquals('spatie/packagist-api', $result['package']['name']);
        $this->assertArrayHasKey('total', $result['package']['downloads']);
        $this->assertArrayHasKey('monthly', $result['package']['downloads']);
        $this->assertArrayHasKey('daily', $result['package']['downloads']);
    }

    /** @test */
    public function it_can_get_a_package_via_the_repository()
    {
        $client = $this->client();

        $result = $client->getPackageMetadata('spatie', 'packagist-api');

        $this->assertArrayHasKey('packages', $result);
        $this->assertArrayHasKey('spatie/packagist-api', $result['packages']);
        $this->assertArrayHasKey('dev-master', $result['packages']['spatie/packagist-api']);
    }

    /** @test */
    public function it_can_get_the_statistics()
    {
        $client = $this->client();

        $result = $client->getStatistics();

        $this->assertArrayHasKey('totals', $result);
        $this->assertArrayHasKey('downloads', $result['totals']);
    }

    /**
     * Create a client and fake the given endpoint
     *
     * @return PackagistClient
     */
    private function client()
    {
        $http = new Client();

        return new PackagistClient($http, new PackagistUrlGenerator());
    }
}

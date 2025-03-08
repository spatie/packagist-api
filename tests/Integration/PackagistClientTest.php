<?php

namespace Spatie\Packagist\Test\Integration;

use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Spatie\Packagist\PackagistClient;
use Spatie\Packagist\PackagistUrlGenerator;
use Spatie\Snapshots\MatchesSnapshots;

class PackagistClientTest extends TestCase
{
    use MatchesSnapshots;

    #[Test]
    public function it_can_list_package_names()
    {
        $client = $this->client();

        $result = $client->getPackagesNames();

        $this->assertArrayHasKey('packageNames', $result);
    }

    #[Test]
    public function it_can_list_package_names_for_a_vendor()
    {
        $client = $this->client();

        $result = $client->getPackagesNamesByVendor('spatie');

        $first10Packages = array_slice($result['packageNames'], 0, 10);

        foreach ($first10Packages as $package) {
            $this->assertStringStartsWith('spatie/', $package);
        }
    }

    #[Test]
    public function it_can_list_package_names_for_a_type()
    {
        $client = $this->client();

        $result = $client->getPackagesNamesByType('composer-plugin');
        $firstRepository = current($result['packageNames']);

        $metadata = $client->getPackageMetadata($firstRepository);

        $this->assertEquals('composer-plugin', $metadata['packages'][$firstRepository][0]['type']);
    }

    #[Test]
    public function it_can_list_popular_packages()
    {
        $client = $this->client();

        $result = $client->getPopularPackages();

        $this->assertArrayHasKey('packages', $result);
        $this->assertIsArray($result['packages']);
    }

    #[Test]
    public function it_can_search_packages_by_name()
    {
        $client = $this->client();

        $result = $client->searchPackagesByName('monolog');

        $this->assertArrayHasKey('results', $result);
        $this->assertIsArray($result['results']);
    }

    #[Test]
    public function it_can_set_the_page_size_when_searching()
    {
        $client = $this->client();

        $result = $client->searchPackages('monolog', [], 1, 2);

        $this->assertArrayHasKey('results', $result);
        $this->assertCount(2, $result['results']);
    }

    #[Test]
    public function it_can_search_packages_by_type()
    {
        $client = $this->client();

        $result = $client->searchPackagesByType('symfony-bundle');
        $firstRepository = current($result['results']);

        $name = $firstRepository['name'];
        $metadata = $client->getPackageMetadata($name);

        $this->assertEquals('symfony-bundle', $metadata['packages'][$name][0]['type']);
    }

    #[Test]
    public function it_can_search_packages_by_tags()
    {
        $client = $this->client();

        $result = $client->searchPackagesByTags('psr-7');
        $firstRepository = current($result['results']);

        $this->assertArrayHasKey('name', $firstRepository);

        $name = $firstRepository['name'];
        $metadata = $client->getPackageMetadata($name);

        $this->assertContains('psr-7', $metadata['packages'][$name][0]['keywords']);
    }

    #[Test]
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

    #[Test]
    public function it_can_get_a_package_via_the_repository()
    {
        $client = $this->client();

        $result = $client->getPackageMetadata('spatie', 'packagist-api');

        $this->assertArrayHasKey('packages', $result);
        $this->assertArrayHasKey('spatie/packagist-api', $result['packages']);

        $version100Release = array_filter(
            $result['packages']['spatie/packagist-api'],
            static fn (array $branch) => $branch['version'] === '1.0.0'
        );

        $this->assertCount(1, $version100Release);
    }

    #[Test]
    public function it_can_get_a_packages_dev_branches_via_the_repository()
    {
        $client = $this->client();

        $result = $client->getPackageMetadata('spatie', 'packagist-api', true);

        $this->assertArrayHasKey('packages', $result);
        $this->assertArrayHasKey('spatie/packagist-api', $result['packages']);

        $mainBranch = array_filter(
            $result['packages']['spatie/packagist-api'],
            static fn (array $branch) => $branch['version'] === 'dev-main'
        );

        $this->assertCount(1, $mainBranch);
    }

    #[Test]
    public function it_can_get_a_packages_download_stats()
    {
        $client = $this->client();

        $result = $client->getPackageDownloadStats('spatie', 'packagist-api');

        $this->assertArrayHasKey('downloads', $result);
        $this->assertArrayHasKey('versions', $result);
    }

    #[Test]
    public function it_can_get_the_statistics()
    {
        $client = $this->client();

        $result = $client->getStatistics();

        $this->assertArrayHasKey('totals', $result);
        $this->assertArrayHasKey('downloads', $result['totals']);
    }

    #[Test]
    public function it_can_get_advisories_by_package_name()
    {
        $client = $this->client();

        $result = $client->getAdvisories(['silverstripe/admin']);

        $this->assertArrayHasKey('silverstripe/admin', $result);

        $this->assertMatchesJsonSnapshot($result);
    }

    #[Test]
    public function it_can_get_filtered_advisories_by_package_name()
    {
        $client = $this->client();

        $result = $client->getAdvisoriesAffectingVersions(['silverstripe/admin' => '1.5.0']);

        $this->assertMatchesJsonSnapshot($result);
    }

    #[Test]
    public function it_can_get_advisories_by_timestamp()
    {
        $client = $this->client();

        $result = $client->getAdvisories(['microweber/microweber'], 1656670429);

        $this->assertArrayHasKey('microweber/microweber', $result);
    }

    private function client(): PackagistClient
    {
        return new PackagistClient(new Client, new PackagistUrlGenerator);
    }
}

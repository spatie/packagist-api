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

        $first10Packages = array_slice($result['packageNames'], 0, 10);

        foreach ($first10Packages as $package) {
            $this->assertStringStartsWith('spatie/', $package);
        }
    }

    /** @test */
    public function it_can_list_package_names_for_a_type()
    {
        $client = $this->client();

        $result = $client->getPackagesNamesByType('composer-plugin');
        $firstRepository = current($result['packageNames']);

        $metadata = $client->getPackageMetadata($firstRepository);

        $latestVersion = end($metadata['packages'][$firstRepository]);

        $this->assertEquals('composer-plugin', $latestVersion['type']);
    }

    /** @test */
    public function it_can_search_packages_by_name()
    {
        $client = $this->client();

        $result = $client->searchPackagesByName('monolog');

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

        $result = $client->searchPackagesByType('symfony-bundle');
        $firstRepository = current($result['results']);

        $name = $firstRepository['name'];
        $metadata = $client->getPackageMetadata($name);

        $latestVersion = end($metadata['packages'][$name]);

        $this->assertEquals('symfony-bundle', $latestVersion['type']);
    }

    /** @test */
    public function it_can_search_packages_by_tags()
    {
        $client = $this->client();

        $result = $client->searchPackagesByTags('psr-7');
        $firstRepository = current($result['results']);

        $this->assertArrayHasKey('name', $firstRepository);

        $name = $firstRepository['name'];
        $metadata = $client->getPackageMetadata($name);

        $latestVersion = end($metadata['packages'][$name]);

        $this->assertContains('psr-7', $latestVersion['keywords']);
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

    /** @test */
    public function it_can_get_filtered_advisories_by_package_name()
    {
        $client = $this->client();

        $result = $client->getAdvisories(['silverstripe/admin' => '1.5.0'], null, true);

        $this->assertArrayHasKey('silverstripe/admin', $result);
        $advisory = [
            'advisoryId' => 'PKSA-zmvy-dmwz-zrvp',
            'packageName' => 'silverstripe/admin',
            'remoteId' => 'silverstripe/admin/CVE-2021-36150.yaml',
            'title' => 'CVE-2021-36150 - Insert from files link text - Reflective (self) Cross Site Scripting',
            'link' => 'https://www.silverstripe.org/download/security-releases/CVE-2021-36150',
            'cve' => 'CVE-2021-36150',
            'affectedVersions' => '>=1.0.0,<1.8.1',
            'source' => 'FriendsOfPHP/security-advisories',
            'reportedAt' => '2021-10-05 05:18:20',
            'composerRepository' => 'https://packagist.org',
            'sources' => [
                [
                    'name' => 'GitHub',
                    'remoteId' => 'GHSA-j66h-cc96-c32q',
                ],
                [
                    'name' => 'FriendsOfPHP/security-advisories',
                    'remoteId' => 'silverstripe/admin/CVE-2021-36150.yaml',
                ],
            ],
        ];
        $this->assertContains($advisory, $result['silverstripe/admin']);
    }

    /** @test */
    public function it_can_get_unfiltered_advisories_by_package_name()
    {
        $client = $this->client();

        $result = $client->getAdvisories(['silverstripe/admin'], null, false);

        $this->assertArrayHasKey('silverstripe/admin', $result);
        $advisories = [
            [
                'advisoryId' => 'PKSA-zmvy-dmwz-zrvp',
                'packageName' => 'silverstripe/admin',
                'remoteId' => 'silverstripe/admin/CVE-2021-36150.yaml',
                'title' => 'CVE-2021-36150 - Insert from files link text - Reflective (self) Cross Site Scripting',
                'link' => 'https://www.silverstripe.org/download/security-releases/CVE-2021-36150',
                'cve' => 'CVE-2021-36150',
                'affectedVersions' => '>=1.0.0,<1.8.1',
                'source' => 'FriendsOfPHP/security-advisories',
                'reportedAt' => '2021-10-05 05:18:20',
                'composerRepository' => 'https://packagist.org',
                'sources' => [
                    [
                        'name' => 'GitHub',
                        'remoteId' => 'GHSA-j66h-cc96-c32q',
                    ],
                    [
                        'name' => 'FriendsOfPHP/security-advisories',
                        'remoteId' => 'silverstripe/admin/CVE-2021-36150.yaml',
                    ],
                ],
            ],
            [
                'advisoryId' => 'PKSA-wvzh-yq7r-9q1d',
                'packageName' => 'silverstripe/admin',
                'remoteId' => 'silverstripe/admin/SS-2018-004-1.yaml',
                'title' => 'SS-2018-004: XSS Vulnerability via WYSIWYG editor',
                'link' => 'https://www.silverstripe.org/download/security-releases/ss-2018-004/',
                'cve' => null,
                'affectedVersions' => '>=1.0.3,<1.0.4|>=1.1.0,<1.1.1',
                'source' => 'FriendsOfPHP/security-advisories',
                'reportedAt' => '2018-02-01 17:33:07',
                'composerRepository' => 'https://packagist.org',
                'sources' => [
                    [
                        'name' => 'FriendsOfPHP/security-advisories',
                        'remoteId' => 'silverstripe/admin/SS-2018-004-1.yaml',
                    ],
                ],
            ],
        ];
        foreach ($advisories as $advisory) {
            $this->assertContains($advisory, $result['silverstripe/admin']);
        }
    }

    /** @test */
    public function it_can_get_advisories_by_timestamp()
    {
        $client = $this->client();

        $result = $client->getAdvisories([], 1656670429);

        $this->assertArrayHasKey('microweber/microweber', $result);
    }

    private function client(): PackagistClient
    {
        $http = new Client();

        return new PackagistClient($http, new PackagistUrlGenerator());
    }
}

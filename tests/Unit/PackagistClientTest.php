<?php

namespace Spatie\Packagist\Test\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Spatie\Packagist\Exceptions\InvalidArgumentException;
use Spatie\Packagist\PackagistClient;
use Spatie\Packagist\PackagistUrlGenerator;

class PackagistClientTest extends TestCase
{
    /** @test */
    public function it_can_list_package_names()
    {
        $client = $this->packagistClientWithMockedHttp('api.test/packages/list.json');

        $result = $client->getPackagesNames();

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_list_package_names_by_type_and_vendor()
    {
        $client = $this->packagistClientWithMockedHttp('api.test/packages/list.json', ['type' => 'composer-plugin', 'vendor' => 'spatie']);

        $result = $client->getPackagesNames('composer-plugin', 'spatie');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_list_package_names_for_a_vendor()
    {
        $client = $this->packagistClientWithMockedHttp('api.test/packages/list.json', ['vendor' => 'spatie']);

        $result = $client->getPackagesNamesByVendor('spatie');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_list_package_names_for_a_type()
    {
        $client = $this->packagistClientWithMockedHttp('api.test/packages/list.json', ['type' => 'composer-plugin']);

        $result = $client->getPackagesNamesByType('composer-plugin');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_search_packages()
    {
        $client = $this->packagistClientWithMockedHttp(
            'api.test/search.json',
            ['q' => 'keyword', 'tags' => 'tag-1', 'type' => 'composer-plugin', 'page' => 1, 'per_page' => 15]
        );

        $result = $client->searchPackages('keyword', ['tags' => 'tag-1', 'type' => 'composer-plugin']);

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_set_the_pagination_settings_while_searching()
    {
        $client = $this->packagistClientWithMockedHttp(
            'api.test/search.json',
            ['q' => 'keyword', 'page' => 2, 'per_page' => 20]
        );

        $result = $client->searchPackages('keyword', [], 2, 20);

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_search_packages_by_name()
    {
        $client = $this->packagistClientWithMockedHttp('api.test/search.json', ['q' => 'keyword', 'page' => 1, 'per_page' => 15]);

        $result = $client->searchPackagesByName('keyword');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_search_packages_by_type()
    {
        $client = $this->packagistClientWithMockedHttp('api.test/search.json', ['q' => 'spatie', 'type' => 'composer-plugin', 'page' => 1, 'per_page' => 15]);

        $result = $client->searchPackagesByType('composer-plugin', 'spatie');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_search_packages_by_tags()
    {
        $client = $this->packagistClientWithMockedHttp('api.test/search.json', ['q' => 'spatie', 'tags' => 'psr-7', 'page' => 1, 'per_page' => 15]);

        $result = $client->searchPackagesByTags('psr-7', 'spatie');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_throws_an_exception_when_an_invalid_filter_is_appended_to_the_search_action()
    {
        $this->expectException(InvalidArgumentException::class);
        $client = new PackagistClient(new Client(), new PackagistUrlGenerator('api.test', 'repo.test'));

        $client->searchPackages('spatie', ['invalid-filter' => 'value']);
    }

    /** @test */
    public function it_can_get_a_package_via_the_api()
    {
        $client = $this->packagistClientWithMockedHttp('api.test/packages/spatie/packagist-api.json');

        $result = $client->getPackage('spatie', 'packagist-api');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_get_a_package_via_the_repository()
    {
        $client = $this->packagistClientWithMockedHttp('repo.test/p/spatie/packagist-api.json');

        $result = $client->getPackageMetadata('spatie', 'packagist-api');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_get_the_statistics()
    {
        $client = $this->packagistClientWithMockedHttp('api.test/statistics.json');

        $result = $client->getStatistics();

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_filters_the_advisories_by_package_version()
    {
        $mock = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $client = new PackagistClient($mock, new PackagistUrlGenerator());
        $filterAdvisoriesReflection = new ReflectionMethod($client, 'filterAdvisories');
        $filterAdvisoriesReflection->setAccessible(true);

        $packages = [
            'missing/package' => '1.0.0',
            'matches1/package' => '1.2.3',
            'matches2/package' => '7.0.0',
            'no-match/package' => '3.4.5',
        ];
        $advisories = [
            'matches1/package' => [
                [
                    'title' => 'advisory2',
                    'affectedVersions' => '>=1.0.0,<1.8.1',
                ],
                [
                    'title' => 'advisory3',
                    'affectedVersions' => '>=1.2.3,<1.2.4',
                ],
            ],
            'matches2/package' => [
                [
                    'title' => 'advisory4',
                    'affectedVersions' => '>=1.0.0,<1.8.1',
                ],
                [
                    'title' => 'advisory5',
                    'affectedVersions' => '>=7.0.0,<7.8.1',
                ],
            ],
            'no-match/package' => [
                [
                    'title' => 'advisory6',
                    'affectedVersions' => '>=1.0.0,<1.8.1',
                ],
            ],
            'not-requested/package' => [
                [
                    'title' => 'advisory7',
                    'affectedVersions' => '>=1.0.0,<1.8.1',
                ],
            ],
        ];

        $expected = [
            'matches1/package' => [
                [
                    'title' => 'advisory2',
                    'affectedVersions' => '>=1.0.0,<1.8.1',
                ],
                [
                    'title' => 'advisory3',
                    'affectedVersions' => '>=1.2.3,<1.2.4',
                ],
            ],
            'matches2/package' => [
                [
                    'title' => 'advisory5',
                    'affectedVersions' => '>=7.0.0,<7.8.1',
                ],
            ],
        ];

        $filtered = $filterAdvisoriesReflection->invoke($client, $advisories, $packages);
        $this->assertEquals($expected, $filtered);
    }

    /** @test */
    public function it_throws_exception_on_bad_advisory_inputs()
    {
        $mock = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $client = new PackagistClient($mock, new PackagistUrlGenerator());

        $this->expectException(InvalidArgumentException::class);

        $client->getAdvisories([], null);
    }

    /**
     * @param string $url
     * @param array  $query
     *
     * @return PackagistClient
     */
    private function packagistClientWithMockedHttp(string $url, array $query = []): PackagistClient
    {
        $mock = $this->getMock($url, $query);

        return new PackagistClient($mock, new PackagistUrlGenerator('api.test', 'repo.test'));
    }

    /**
     * @param string $url
     * @param array  $query
     *
     * @return Client|MockObject
     */
    private function getMock(string $url, array $query = [])
    {
        $mock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        $mock->expects($this->once())
            ->method('get')
            ->with($url, compact('query'))
            ->willReturnCallback(function () {
                return new Response(200, [], json_encode(['result' => 'ok']));
            });

        return $mock;
    }
}

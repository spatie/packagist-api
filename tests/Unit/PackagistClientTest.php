<?php

namespace Spatie\Packagist\Test\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spatie\Packagist\PackagistClient;
use Spatie\Packagist\PackagistUrlGenerator;

class PackagistClientTest extends TestCase
{

    /** @test */
    public function it_can_list_package_names()
    {
        $client = $this->client('api.test/packages/list.json');

        $result = $client->getPackagesNames();

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_list_package_names_by_type_and_vendor()
    {
        $client = $this->client('api.test/packages/list.json', ['type' => 'composer-plugin', 'vendor' => 'spatie']);

        $result = $client->getPackagesNames('composer-plugin', 'spatie');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_list_package_names_for_a_vendor()
    {
        $client = $this->client('api.test/packages/list.json', ['vendor' => 'spatie']);

        $result = $client->getPackagesNamesByVendor('spatie');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_list_package_names_for_a_type()
    {
        $client = $this->client('api.test/packages/list.json', ['type' => 'composer-plugin']);

        $result = $client->getPackagesNamesByType('composer-plugin');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_search_packages()
    {
        $client = $this->client(
            'api.test/search.json',
            ['q' => 'keyword', 'tags' => 'tag-1', 'type' => 'composer-plugin', 'page' => 1, 'per_page' => 15]
        );

        $result = $client->searchPackages('keyword', ['tags' => 'tag-1', 'type' => 'composer-plugin']);

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_set_the_pagination_settings_while_searching()
    {
        $client = $this->client(
            'api.test/search.json',
            ['q' => 'keyword', 'page' => 2, 'per_page' => 20]
        );

        $result = $client->searchPackages('keyword', [], 2, 20);

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_search_packages_by_name()
    {
        $client = $this->client('api.test/search.json', ['q' => 'keyword', 'page' => 1, 'per_page' => 15]);

        $result = $client->searchPackagesByName('keyword');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_search_packages_by_type()
    {
        $client = $this->client('api.test/search.json', ['q' => 'spatie', 'type' => 'composer-plugin', 'page' => 1, 'per_page' => 15]);

        $result = $client->searchPackagesByType('composer-plugin', 'spatie');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /** @test */
    public function it_can_search_packages_by_tags()
    {
        $client = $this->client('api.test/search.json', ['q' => 'spatie', 'tags' => 'psr-7', 'page' => 1, 'per_page' => 15]);

        $result = $client->searchPackagesByTags('psr-7', 'spatie');

        $this->assertEquals($result, ['result' => 'ok']);
    }

    /**
     * Create a client and fake the given endpoint
     *
     * @param string $url
     * @param array  $query
     *
     * @return PackagistClient
     */
    private function client(string $url, array $query = [])
    {
        $mock = $this->getMock($url, $query);

        return new PackagistClient($mock, new PackagistUrlGenerator('api.test', 'repo.test'));
    }

    /**
     * Mock an instance of the Guzzle client.
     *
     * @param string $url
     * @param array  $query
     *
     * @return Client|MockObject
     */
    private function getMock(string $url, array $query = [])
    {
        $mock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->addMethods(['get'])
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

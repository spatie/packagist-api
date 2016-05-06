<?php

namespace Spatie\PackagistApi;

use GuzzleHttp\Client;

class PackagistApi
{
    /** @var string */
    protected $baseUrl;

    /** @var \GuzzleHttp\Client */
    private $client;

    /** Create a new PackagistApi Instance */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->baseUrl = 'https://packagist.org';
    }

    public function getVendorPackages(string $vendor)
    {
        return $this->get('/packages/list.json', ['vendor' => $vendor]);
    }

    public function getPackageByName(string $vendor, string $package)
    {
        return $this->get("/packages/{$vendor}/{$package}.json");
    }

    public function searchPackagesByTag(string $tag)
    {
        return $this->get('/search.json', ['tags' => $tag]);
    }

    public function searchPackagesByName(string $name)
    {
        return $this->get('/search.json', ['q' => $name]);
    }

    protected function get(string $resource, array $query = [])
    {
        $packages = $this->client
            ->get("{$this->baseUrl}{$resource}", ['query' => $query])
            ->getBody()
            ->getContents();

        return json_decode($packages);
    }
}

<?php

namespace Spatie\Packagist;

use GuzzleHttp\Client;

class Packagist
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var string */
    protected $baseUrl;

    /**
     * @param \GuzzleHttp\Client $client
     * @param string             $baseUrl
     */
    public function __construct(Client $client, $baseUrl = 'https://packagist.org')
    {
        $this->client = $client;

        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $vendor
     *
     * @return array
     */
    public function getPackagesByVendor($vendor)
    {
        return $this->makeRequest('/packages/list.json', compact('vendor'));
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function getPackagesByName($name)
    {
        return $this->makeRequest('/search.json', ['q' => $name]);
    }

    /**
     * @param $vendor
     * @param string $packageName
     *
     * @return array
     */
    public function findPackageByName($vendor, $packageName = '')
    {
        if ($packageName === '') {
            list($vendor, $packageName) = explode('/', $vendor);
        }

        return $this->makeRequest("/packages/{$vendor}/{$packageName}.json");
    }

    /**
     * @param string $resource
     * @param array  $query
     *
     * @return array
     */
    public function makeRequest($resource, array $query = [])
    {
        $packages = $this->client
            ->get("{$this->baseUrl}{$resource}", compact('query'))
            ->getBody()
            ->getContents();

        return json_decode($packages, true);
    }
}

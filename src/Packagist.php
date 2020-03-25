<?php

namespace Spatie\Packagist;

use GuzzleHttp\Client;
use InvalidArgumentException;

class Packagist
{
    /** @var Client */
    protected $client;

    /** @var string */
    protected $baseUrl;

    /**
     * @param Client $client
     * @param string $baseUrl
     */
    public function __construct(Client $client, $baseUrl = 'https://packagist.org')
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getPackagesByType($type)
    {
        if (empty($type)) {
            throw new InvalidArgumentException('You must pass a non-empty value');
        }

        return $this->makeRequest('/packages/list.json', compact('type'));
    }

    /**
     * @param string $vendor
     *
     * @return array
     */
    public function getPackagesByVendor($vendor)
    {
        if (empty($vendor)) {
            throw new InvalidArgumentException('You must pass a non empty value');
        }

        return $this->makeRequest('/packages/list.json', compact('vendor'));
    }

    /**
     * @param string $name
     * @param string $type
     *
     * @return array
     */
    public function getPackagesByName($name, $type = '')
    {
        $query = ['q' => $name];

        if ($type != '') {
            $query['type'] = $type;
        }

        return $this->makeRequest('/search.json', $query);
    }

    /**
     * @param string $vendor
     * @param string $packageName
     *
     * @return array
     */
    public function findPackageByName($vendor, $packageName = '')
    {
        if ($packageName === '') {
            if (strpos($vendor, '/') === false) {
                throw new InvalidArgumentException('Invalid package name');
            }
            [$vendor, $packageName] = explode('/', $vendor);
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

    /**
     * @param string $name
     *
     * @return array
     */
    public function getPackageMetadata($name)
    {
        if ($name === '') {
            throw new InvalidArgumentException('You must pass a non empty value');
        }

        [$vendor, $packageName] = explode('/', $name);

        return $this->makeRequest("/packages/{$vendor}/{$packageName}.json");
    }
}

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

    public function __construct(Client $client, string $baseUrl = 'https://packagist.org')
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    public function getPackagesByType(string $type): ?array
    {
        if (empty($type)) {
            throw new InvalidArgumentException('You must pass a non-empty value');
        }

        return $this->makeRequest('/packages/list.json', compact('type'));
    }

    public function getPackagesByVendor(string $vendor): ?array
    {
        if (empty($vendor)) {
            throw new InvalidArgumentException('You must pass a non empty value');
        }

        return $this->makeRequest('/packages/list.json', compact('vendor'));
    }

    public function getPackagesByName(string $name, ?string $type = null): ?array
    {
        $query = ['q' => $name];

        if (is_null($type) === false && strlen($type) > 0) {
            $query['type'] = $type;
        }

        return $this->makeRequest('/search.json', $query);
    }

    public function findPackageByName(string $vendor, ?string $package = null): ?array
    {
        if (is_null($package) || strlen($package) === 0) {
            if (strpos($vendor, '/') === false) {
                throw new InvalidArgumentException('Invalid package name');
            }
            [$vendor, $package] = explode('/', $vendor);
        }

        return $this->makeRequest("/packages/{$vendor}/{$package}.json");
    }

    public function getPackageMetadata(string $name)
    {
        if ($name === '') {
            throw new InvalidArgumentException('You must pass a non empty value');
        }

        [$vendor, $package] = explode('/', $name);

        return $this->makeRequest("/packages/{$vendor}/{$package}.json");
    }

    public function makeRequest(string $resource, array $query = []): ?array
    {
        $packages = $this->client
            ->get("{$this->baseUrl}{$resource}", compact('query'))
            ->getBody()
            ->getContents();

        return json_decode($packages, true);
    }
}

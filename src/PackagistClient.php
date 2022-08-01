<?php

namespace Spatie\Packagist;

use Composer\Semver\Semver;
use GuzzleHttp\Client;
use Spatie\Packagist\Exceptions\InvalidArgumentException;

class PackagistClient
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var \Spatie\Packagist\PackagistUrlGenerator */
    protected $url;

    public function __construct(Client $client, PackagistUrlGenerator $url)
    {
        $this->client = $client;

        $this->url = $url;
    }

    public function getPackagesNames(?string $type = null, ?string $vendor = null): ?array
    {
        return $this->request('packages/list.json', array_filter(compact('type', 'vendor')));
    }

    public function getPackagesNamesByType(string $type): ?array
    {
        return $this->getPackagesNames($type, null);
    }

    public function getPackagesNamesByVendor(string $vendor): ?array
    {
        return $this->getPackagesNames(null, $vendor);
    }

    public function searchPackages($name = null, array $filters = [], ?int $page = 1, int $perPage = 15): ?array
    {
        if (count(array_diff(array_keys($filters), ['tags', 'type'])) > 0) {
            throw new InvalidArgumentException('Cannot search packages on this parameter. Allowed: `tags` & `type`.');
        }

        $filters['q'] = $name;
        $filters['page'] = $page;
        $filters['per_page'] = $perPage;

        return $this->request('search.json', $filters);
    }

    public function searchPackagesByName(string $name, ?int $page = 1, int $perPage = 15): ?array
    {
        return $this->searchPackages($name, [], $page, $perPage);
    }

    public function searchPackagesByTags(string $tags, ?string $name = null, ?int $page = 1, int $perPage = 15): ?array
    {
        return $this->searchPackages($name, ['tags' => $tags], $page, $perPage);
    }

    public function searchPackagesByType(string $type, ?string $name = null, ?int $page = 1, int $perPage = 15): ?array
    {
        return $this->searchPackages($name, ['type' => $type], $page, $perPage);
    }

    public function getPackage(string $vendor, ?string $package = null): ?array
    {
        [$vendor, $package] = PackagistVendorFormatter::format($vendor, $package);
        $resource = 'packages/'.$vendor.'/'.$package.'.json';

        return $this->request($resource, [], PackagistUrlGenerator::API_MODE);
    }

    public function getPackageMetadata(string $vendor, ?string $package = null): ?array
    {
        [$vendor, $package] = PackagistVendorFormatter::format($vendor, $package);
        $resource = 'p/'.$vendor.'/'.$package.'.json';

        return $this->request($resource, [], PackagistUrlGenerator::REPO_MODE);
    }

    public function getStatistics(): ?array
    {
        return $this->request('statistics.json');
    }

    public function getAdvisories(array $packages = [], ?int $updatedSince = null): array
    {
        if (count($packages) === 0) {
            throw new InvalidArgumentException(
                'At least one package must be passed in.'
            );
        }

        $query = [];

        if ($updatedSince !== null) {
            $query['updatedSince'] = $updatedSince;
        }

        $options = [
            'query' => array_filter($query),
        ];

        $content = ['packages' => []];

        foreach ($packages as $package => $version) {
            if (is_numeric($package)) {
                $package = $version;
            }
            $content['packages'][] = $package;
        }
        $options['headers']['Content-type'] = 'application/x-www-form-urlencoded';
        $options['body'] = http_build_query($content);

        $response = $this->postRequest('api/security-advisories/', $options);

        if ($response === null) {
            return [];
        }

        $advisories = $response['advisories'];

        return $advisories;
    }

    public function getAdvisoriesAffectingVersions(array $packages = [], ?int $updatedSince = null): array
    {
        $advisories = $this->getAdvisories($packages, $updatedSince);

        if (count($advisories) > 0) {
            return $this->filterAdvisories($advisories, $packages);
        }

        return $advisories;
    }

    protected function filterAdvisories(array $advisories, array $packages): array
    {
        $filteredAdvisories = [];
        foreach ($packages as $package => $version) {
            if (! is_string($package) || ! is_string($version)) {
                throw new InvalidArgumentException('$packages array must have package names as keys and versions as values.');
            }

            if (array_key_exists($package, $advisories)) {
                foreach ($advisories[$package] as $advisory) {
                    if (Semver::satisfies($version, $advisory['affectedVersions'])) {
                        $filteredAdvisories[$package][] = $advisory;
                    }
                }
            }
        }

        return $filteredAdvisories;
    }

    public function postRequest(string $resource, array $options = [], string $mode = PackagistUrlGenerator::API_MODE): ?array
    {
        $url = $this->url->make($resource, $mode);
        $response = $this->client
            ->post($url, $options)
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    public function request(string $resource, array $query = [], string $mode = PackagistUrlGenerator::API_MODE): ?array
    {
        $url = $this->url->make($resource, $mode);
        $response = $this->client
            ->get($url, ['query' => array_filter($query)])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }
}

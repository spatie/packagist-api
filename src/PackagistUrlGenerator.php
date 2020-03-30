<?php

namespace Spatie\Packagist;

use Spatie\Packagist\Exceptions\InvalidArgumentException;

class PackagistUrlGenerator
{
    public const API_MODE = 'base_url';
    public const REPO_MODE = 'repo_url';

    /**
     * @var array
     */
    private $config;

    /**
     * PackagistManager constructor.
     *
     * @param string $baseUrl
     * @param string $repoUrl
     */
    public function __construct(?string $baseUrl = null, ?string $repoUrl = null)
    {
        $config[self::API_MODE] = $this->formatUrl($baseUrl ?? 'https://packagist.org');
        $config[self::REPO_MODE] = $this->formatUrl($repoUrl ?? 'https://repo.packagist.org');

        $this->config = $config;
    }

    /**
     * Build a url based on the given string.
     *
     * @param string $resource
     * @param string $mode
     *
     * @return string
     */
    public function make(string $resource = '', string $mode = self::API_MODE): string
    {
        if (in_array($mode, [self::API_MODE, self::REPO_MODE]) === false) {
            throw new InvalidArgumentException("Mode '{$mode}' is not supported. Use the constants of the `PackagistUrlGenerator` to decide which mode to use.");
        }
        return $this->config[$mode] . $resource;
    }

    /**
     * Finish the url with a forward slash for consistency.
     *
     * @param string $url
     *
     * @return string
     */
    private function formatUrl(string $url): string
    {
        return preg_replace('/(?:\/)+$/u', '', $url).'/';
    }
}

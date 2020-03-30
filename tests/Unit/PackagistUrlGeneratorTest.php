<?php

namespace Spatie\Packagist\Test\Unit;

use GuzzleHttp\Client;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Spatie\Packagist\Packagist;
use Spatie\Packagist\PackagistUrlGenerator;

class PackagistUrlGeneratorTest extends TestCase
{

    /** @test */
    public function it_can_generate_a_url_for_an_api_call()
    {
        $generator = new PackagistUrlGenerator();

        $result = $generator->make('packages/list.json', PackagistUrlGenerator::API_MODE);

        $this->assertEquals('https://packagist.org/packages/list.json', $result);
    }

    /** @test */
    public function it_can_generate_a_url_for_a_repo_call()
    {
        $generator = new PackagistUrlGenerator();

        $result = $generator->make('monolog/monolog.json', PackagistUrlGenerator::REPO_MODE);

        $this->assertEquals('https://repo.packagist.org/monolog/monolog.json', $result);
    }

    /** @test */
    public function it_can_override_url_configuration()
    {
        $generator = new PackagistUrlGenerator('https://api.packagist.com/', 'https://repository.packagist.com/');

        $resultA = $generator->make('a/b.json', PackagistUrlGenerator::API_MODE);
        $resultB = $generator->make('a/b.json', PackagistUrlGenerator::REPO_MODE);

        $this->assertEquals('https://api.packagist.com/a/b.json', $resultA);
        $this->assertEquals('https://repository.packagist.com/a/b.json', $resultB);
    }

    /** @test */
    public function it_always_appends_a_forward_slash_to_the_url()
    {
        $generator = new PackagistUrlGenerator('https://api.packagist.com', 'https://repository.packagist.com');

        $resultA = $generator->make('', PackagistUrlGenerator::API_MODE);
        $resultB = $generator->make('', PackagistUrlGenerator::REPO_MODE);

        $this->assertEquals('https://api.packagist.com/', $resultA);
        $this->assertEquals('https://repository.packagist.com/', $resultB);
    }
}

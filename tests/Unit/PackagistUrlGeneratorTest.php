<?php

namespace Spatie\Packagist\Test\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Spatie\Packagist\Exceptions\InvalidArgumentException;
use Spatie\Packagist\PackagistUrlGenerator;

class PackagistUrlGeneratorTest extends TestCase
{
    #[Test]
    public function it_can_generate_a_url_for_an_api_call()
    {
        $generator = new PackagistUrlGenerator;

        $result = $generator->make('packages/list.json', PackagistUrlGenerator::API_MODE);

        $this->assertSame('https://packagist.org/packages/list.json', $result);
    }

    #[Test]
    public function it_can_generate_a_url_for_a_repo_call()
    {
        $generator = new PackagistUrlGenerator;

        $result = $generator->make('monolog/monolog.json', PackagistUrlGenerator::REPO_MODE);

        $this->assertSame('https://repo.packagist.org/monolog/monolog.json', $result);
    }

    #[Test]
    public function it_can_override_url_configuration()
    {
        $generator = new PackagistUrlGenerator('https://api.packagist.com/', 'https://repository.packagist.com/');

        $resultA = $generator->make('a/b.json', PackagistUrlGenerator::API_MODE);
        $resultB = $generator->make('a/b.json', PackagistUrlGenerator::REPO_MODE);

        $this->assertSame('https://api.packagist.com/a/b.json', $resultA);
        $this->assertSame('https://repository.packagist.com/a/b.json', $resultB);
    }

    #[Test]
    public function it_always_appends_a_forward_slash_to_the_url()
    {
        $generator = new PackagistUrlGenerator('https://api.packagist.com', 'https://repository.packagist.com');

        $resultA = $generator->make('', PackagistUrlGenerator::API_MODE);
        $resultB = $generator->make('', PackagistUrlGenerator::REPO_MODE);

        $this->assertSame('https://api.packagist.com/', $resultA);
        $this->assertSame('https://repository.packagist.com/', $resultB);
    }

    #[Test]
    public function it_throws_an_exception_when_an_invalid_mode_is_used()
    {
        $this->expectException(InvalidArgumentException::class);
        $generator = new PackagistUrlGenerator;

        $generator->make('', 'invalid-mode');
    }
}

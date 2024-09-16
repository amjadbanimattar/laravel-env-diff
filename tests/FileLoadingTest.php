<?php

declare(strict_types=1);

namespace AmjadBM\EnvDiff\Tests;

use AmjadBM\EnvDiff\Services\DiffService;
use AmjadBM\EnvDiff\Tests\TestCases\TestCase;
use Dotenv\Exception\InvalidPathException;

class FileLoadingTest extends TestCase
{
    /**
     * @test
     */
    public function missingFile()
    {
        $this->expectException(InvalidPathException::class);

        $service = new DiffService(__DIR__ . '/Support');
        $service->add('.env.missing');
    }

    /**
     * @test
     */
    public function wrongDataGetter()
    {
        $service = new DiffService(__DIR__ . '/Support');
        $service->add('.env');

        $this->assertEquals([], $service->getData('.env.missing'));
    }

    /**
     * @test
     */
    public function singleFileSingleVariable()
    {
        $service = new DiffService(__DIR__ . '/Support');
        $service->add('.env');

        $this->assertEquals([
            'ENV' => 'development',
        ], $service->getData('.env'));
    }

    /**
     * @test
     */
    public function singleFileMultipleVariables()
    {
        $service = new DiffService(__DIR__ . '/Support');
        $service->add('.env.second');

        $this->assertEquals([
            'FOO' => 'bar',
            'BAR' => '1',
        ], $service->getData('.env.second'));
    }
}

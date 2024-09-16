<?php

declare(strict_types=1);

namespace AmjadBM\EnvDiff\Tests;

use AmjadBM\EnvDiff\Services\DiffService;
use AmjadBM\EnvDiff\Tests\TestCases\TestCase;

class DiffTest extends TestCase
{
    /**
     * @test
     */
    public function singleFileEmptyVariables()
    {
        $service = new DiffService();

        $service->setData('.env', []);

        $this->assertEquals([], $service->diff());
    }

    /**
     * @test
     */
    public function multipleFilesEmptyVariables()
    {
        $service = new DiffService();

        $service->setData('.env', []);

        $service->setData('.env.testing', []);

        $this->assertEquals([], $service->diff());
    }

    /**
     * @test
     */
    public function singleFileSingleVariable()
    {
        config(['env-diff.hide_existing' => false]);

        $service = new DiffService();

        $service->setData('.env', [
            'FOO' => 'bar',
        ]);

        $this->assertEquals([
            'FOO' => [
                '.env' => true,
            ],
        ], $service->diff());
    }

    /**
     * @test
     */
    public function singleFileMultipleVariables()
    {
        config(['env-diff.hide_existing' => false]);

        $service = new DiffService();

        $service->setData('.env', [
            'FOO' => 'bar',
            'BAR' => 'foo',
        ]);

        $this->assertEquals([
            'FOO' => [
                '.env' => true,
            ],
            'BAR' => [
                '.env' => true,
            ],
        ], $service->diff());
    }

    /**
     * @test
     */
    public function multipleFilesOneMissing()
    {
        $service = new DiffService();

        $service->setData('.env', [
            'FOO' => 'bar',
        ]);

        $service->setData('.env.testing', []);

        $this->assertEquals([
            'FOO' => [
                '.env' => true,
                '.env.testing' => false,
            ],
        ], $service->diff());
    }

    /**
     * @test
     */
    public function multipleFilesNotCorrespondingAtAll()
    {
        $service = new DiffService();

        $service->setData('.env', [
            'FOO' => 'bar',
        ]);

        $service->setData('.env.testing', [
            'BAR' => 'foo',
        ]);

        $this->assertEquals([
            'FOO' => [
                '.env' => true,
                '.env.testing' => false,
            ],
            'BAR' => [
                '.env' => false,
                '.env.testing' => true,
            ],
        ], $service->diff());
    }

    /**
     * @test
     */
    public function multipleFilesAllExisting()
    {
        $service = new DiffService();

        $service->setData('.env', [
            'NOPE' => true,
            'FOO' => 'bar',
        ]);

        $service->setData('.env.testing', [
            'FOO' => 'foo',
        ]);

        $this->assertEquals([
            'NOPE' => [
                '.env' => true,
                '.env.testing' => false,
            ],
        ], $service->diff());
    }

    /**
     * @test
     */
    public function multipleFilesAllExistingDisabledUnique()
    {
        config(['env-diff.hide_existing' => false]);

        $service = new DiffService();

        $service->setData('.env', [
            'NOPE' => true,
            'FOO' => 'bar',
        ]);

        $service->setData('.env.testing', [
            'FOO' => 'foo',
        ]);

        $this->assertEquals([
            'NOPE' => [
                '.env' => true,
                '.env.testing' => false,
            ],
            'FOO' => [
                '.env' => true,
                '.env.testing' => true,
            ],
        ], $service->diff());
    }
}

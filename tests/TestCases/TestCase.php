<?php

declare(strict_types=1);

namespace AmjadBM\EnvDiff\Tests\TestCases;

use AmjadBM\EnvDiff\Providers\EnvDiffProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config(['env-diff.path' => __DIR__ . '/../Support']);
    }

    protected function getPackageProviders($app)
    {
        return [EnvDiffProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [];
    }
}

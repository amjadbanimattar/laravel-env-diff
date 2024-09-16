<?php

declare(strict_types=1);

namespace AmjadBM\EnvDiff\Tests;

use AmjadBM\EnvDiff\Services\DiffService;
use AmjadBM\EnvDiff\Tests\TestCases\TestCase;

class OutputTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config(['env-diff.use_colors' => false]);
    }

    /**
     * @test
     */
    public function simpleOutput()
    {
        $expect = implode(PHP_EOL, [
            '+----------+------+--------------+',
            '| Variable | .env | .env.missing |',
            '+----------+------+--------------+',
            '| FOO      | Y    | N            |',
            '+----------+------+--------------+',
        ]);

        $expect .= PHP_EOL;

        $this->expectOutputString($expect);

        $service = new DiffService();

        $service->setData('.env', [
            'FOO' => 'bar',
        ]);

        $service->setData('.env.missing', []);

        $service->displayTable();
    }

    /**
     * @test
     */
    public function valueOutput()
    {
        config(['env-diff.show_values' => true]);

        $expect = implode(PHP_EOL, [
            '+----------+------+--------------+',
            '| Variable | .env | .env.missing |',
            '+----------+------+--------------+',
            '| FOO      | bar  | MISSING      |',
            '+----------+------+--------------+',
        ]);

        $expect .= PHP_EOL;

        $this->expectOutputString($expect);

        $service = new DiffService();

        $service->setData('.env', [
            'FOO' => 'bar',
        ]);

        $service->setData('.env.missing', []);

        $service->displayTable();
    }
}

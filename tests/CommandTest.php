<?php

declare(strict_types=1);

namespace AmjadBM\EnvDiff\Tests;

use AmjadBM\EnvDiff\Tests\TestCases\TestCase;

class CommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config([
            'env-diff.use_colors' => false,
            'env-diff.files' => [
                '.env',
                '.env.second',
            ],
        ]);
    }

    /**
     * @test
     */
    public function command()
    {
        $expect = implode(PHP_EOL, [
            '+----------+------+-------------+',
            '| Variable | .env | .env.second |',
            '+----------+------+-------------+',
            '| ENV      | Y    | N           |',
            '| FOO      | N    | Y           |',
            '| BAR      | N    | Y           |',
            '+----------+------+-------------+',
        ]);

        $expect .= PHP_EOL;

        $this->expectOutputString($expect);

        $this->artisan('abm:env:diff');
    }

    /**
     * @test
     */
    public function commandWithValues()
    {
        $expect = implode(PHP_EOL, [
            '+----------+-------------+-------------+',
            '| Variable | .env        | .env.second |',
            '+----------+-------------+-------------+',
            '| ENV      | development | MISSING     |',
            '| FOO      | MISSING     | bar         |',
            '| BAR      | MISSING     | 1           |',
            '+----------+-------------+-------------+',
        ]);

        $expect .= PHP_EOL;

        $this->expectOutputString($expect);

        $this->artisan('abm:env:diff --values');
    }

    /**
     * @test
     */
    public function commandFallbackToDefaultFiles()
    {
        config(['env-diff.files' => []]);

        $expect = implode(PHP_EOL, [
            '+----------+------+',
            '| Variable | .env |',
            '+----------+------+',
        ]);

        $expect .= PHP_EOL;

        $this->expectOutputString($expect);

        $this->artisan('abm:env:diff');
    }

    /**
     * @test
     */
    public function commandWithSpecifiedFile()
    {
        config(['env-diff.files' => []]);

        $expect = implode(PHP_EOL, [
            '+----------+-------------+',
            '| Variable | .env.second |',
            '+----------+-------------+',
        ]);

        $expect .= PHP_EOL;

        $this->expectOutputString($expect);

        $this->artisan('abm:env:diff .env.second');
    }

    /**
     * @test
     */
    public function commandWithMultipleSpecifiedFiles()
    {
        config(['env-diff.files' => []]);

        $expect = implode(PHP_EOL, [
            '+----------+------+-------------+',
            '| Variable | .env | .env.second |',
            '+----------+------+-------------+',
            '| ENV      | Y    | N           |',
            '| FOO      | N    | Y           |',
            '| BAR      | N    | Y           |',
            '+----------+------+-------------+',
        ]);

        $expect .= PHP_EOL;

        $this->expectOutputString($expect);

        $this->artisan('abm:env:diff .env,.env.second');
    }
}

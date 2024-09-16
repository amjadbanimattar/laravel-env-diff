<?php

declare(strict_types=1);

namespace AmjadBM\EnvDiff\Services;

use Dotenv\Dotenv;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;
use Wujunze\Colors;

class DiffService
{
    /**
     * Package configuration.
     *
     * @var array<string, mixed>
     */
    public $config;

    /**
     * File & env variables.
     *
     * @var array<string, array<string, mixed>>
     */
    private $data;

    /**
     * Console table.
     *
     * @var Table|null
     */
    private $table;

    /**
     * @var \Symfony\Component\Console\Output\BufferedOutput
     */
    private $output;

    public function __construct()
    {
        $this->config = config('env-diff');

        $this->table = new Table(
            $this->output = new BufferedOutput()
        );
    }

    /**
     * Add a new .env file and store the loaded data.
     *
     * @param  string|string[]  $file  File name/s
     */
    public function add($file): void
    {
        $files = is_array($file) ? $file : [$file];

        foreach ($files as $envFile) {
            $this->setData(
                $envFile,
                Dotenv::createMutable($this->getPath(), $envFile)->load()
            );
        }
    }

    /**
     * Manually set variable data corresponding to file name.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(string $file, array $data): void
    {
        $this->data[$file] = $data;
    }

    /**
     * Get data.
     *
     *
     * @return array<string, mixed>
     */
    public function getData(?string $file = null): array
    {
        if ($file === null) {
            return $this->data;
        }

        return $this->data[$file] ?? [];
    }

    /**
     * Create a diff of all registered env variables.
     *
     * @return array<string, array<string, bool>>
     */
    public function diff(): array
    {
        $variables = [];

        foreach ($this->data as $file => $vars) {
            foreach ($vars as $key => $value) {
                if (in_array($key, $variables, false)) {
                    continue;
                }

                $variables[] = $key;
            }
        }

        $hideExisting = $this->config['hide_existing'] ?? true;

        $diff = [];

        foreach ($variables as $variable) {
            $containing = [];

            foreach ($this->data as $file => $vars) {
                $containing[$file] = array_key_exists($variable, $vars);
            }

            if ($hideExisting) {
                $unique = array_unique(array_values($containing));

                if (count($unique) === 1 && $unique[0] === true) {
                    continue;
                }
            }

            $diff[$variable] = $containing;
        }

        return $diff;
    }

    /**
     * Build table.
     */
    public function buildTable(): void
    {
        $files = array_keys($this->data);

        $headers = ['Variable'];

        foreach ($files as $file) {
            $headers[] = $file;
        }

        $this->table?->setHeaders($headers);

        $showValues = $this->config['show_values'] ?? false;

        foreach ($this->diff() as $variable => $containing) {
            $row = [$variable];

            foreach ($files as $file) {
                $value = null;

                if (! $showValues) {
                    $value = $this->valueNotFound();

                    if ($containing[$file] === true) {
                        $value = $this->valueOkay();
                    }
                } else {
                    $value = $this->getColoredString('MISSING', 'red');

                    $existing = $this->getData($file)[$variable] ?? null;

                    if ($existing !== null) {
                        $value = $existing;
                    }
                }

                $row[] = $value;
            }

            $this->table?->addRow($row);
        }
    }

    /**
     * Build & display table.
     */
    public function displayTable(): void
    {
        $this->buildTable();

        $this->table?->render();

        echo $this->output->fetch();
    }

    /**
     * Get the base path.
     */
    private function getPath(): string
    {
        return $this->config['path'] ?? base_path();
    }

    /**
     * Get console table string value.
     */
    private function valueOkay(): string
    {
        return $this->getColoredString('Y', 'green');
    }

    /**
     * Get console table string value.
     */
    private function valueNotFound(): string
    {
        return $this->getColoredString('N', 'red');
    }

    /**
     * Color a string for shell output if enabled via config.
     */
    private function getColoredString(string $string, string $color): string
    {
        if (! $this->config['use_colors']) {
            return $string;
        }

        return (new Colors())->getColoredString($string, $color);
    }
}

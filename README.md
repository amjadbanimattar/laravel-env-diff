# Laravel Env Diff

Create a visual Diff of .env and .env.example files

## Installation

```bash
composer require abm/laravel-env-diff:^1.0.0
```

## Configuration

Copy configuration to config folder:

```
$ php artisan vendor:publish --provider="AmjadBM\EnvDiff\Providers\EnvDiffProvider"
```

```php
return [
    /*
     * Specify all environment files that should be compared.
     */
    'files'         => [
        '.env',
        '.env.example',
    ],

    /*
     * The base path to look for environment files.
     */
    'path'          => base_path(),

    /*
     * User colors when printing console output.
     */
    'use_colors'    => true,

    /*
     * Hide variables that exist in all .env files.
     */
    'hide_existing' => true,

    /*
     * Show existing env values instead of y/n.
     */
    'show_values'   => false,
];
```

## Usage

```
$ php artisan abm:env:diff
              {files? : Specify environment files, overriding config}
              {--values : Display existing environment values}';
```

## Example

```
$ php artisan abm:env:diff .env,.env.second
```

```
$ php artisan abm:env:diff .env,.env.second --values
```

## Testing

```shell
./vendor/bin/phpunit
```

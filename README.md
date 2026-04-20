# Pgvector Vitals

[![Tests](https://github.com/illuma-law/healthcheck-pgvector/actions/workflows/run-tests.yml/badge.svg)](https://github.com/illuma-law/healthcheck-pgvector/actions)
[![Packagist License](https://img.shields.io/badge/Licence-MIT-blue)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://img.shields.io/packagist/v/illuma-law/healthcheck-pgvector?label=Version)](https://packagist.org/packages/illuma-law/healthcheck-pgvector)

**Focused pgvector extension health check for Spatie's Laravel Health package**

This package provides a health check to verify the installation and version of the `vector` extension in your PostgreSQL database.

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Testing](#testing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

```bash
composer require illuma-law/healthcheck-pgvector
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="healthcheck-pgvector-config"
```

## Configuration

The configuration file allows you to specify if the extension is required:

```php
return [
    'required' => env('HEALTH_PGVECTOR_REQUIRED', false),
];
```

## Usage

Register the check in your `HealthServiceProvider` or wherever you configure Spatie Health:

```php
use IllumaLaw\HealthCheckPgvector\PgvectorExtensionCheck;
use Spatie\Health\Facades\Health;

Health::checks([
    PgvectorExtensionCheck::new()
        ->required(true),
]);
```

If `required` is set to `true`, the check will fail if the extension is missing. If `false`, it will only issue a warning.

## Testing

The package includes a comprehensive test suite using Pest.

```bash
composer test
```

## Credits

- [illuma-law](https://github.com/illuma-law)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

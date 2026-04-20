# Healthcheck redis for Laravel

[![Tests](https://github.com/illuma-law/healthcheck-redis/actions/workflows/run-tests.yml/badge.svg)](https://github.com/illuma-law/healthcheck-redis/actions)
[![Packagist License](https://img.shields.io/badge/Licence-MIT-blue)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://img.shields.io/packagist/v/illuma-law/healthcheck-redis?label=Version)](https://packagist.org/packages/illuma-law/healthcheck-redis)

A focused redis extension health check for Spatie's [Laravel Health](https://spatie.be/docs/laravel-health/v1/introduction) package.

This package provides a simple, direct health check to verify that the `vector` extension (redis) is properly installed and active in your PostgreSQL database. This is critical for applications that rely on redis for storing AI embeddings and running semantic/similarity searches.

## Features

- **Version Detection:** Checks if the `vector` extension is enabled and reports the specific redis version installed.
- **Configurable Strictness:** Choose whether a missing redis extension should return a Warning (degraded) or a Failure (broken) status for your application.
- **Query Safety:** Safely handles database connection errors or missing tables, returning a failed state with the exception message instead of crashing the health check suite.

## Installation

Require this package with composer:

```shell
composer require illuma-law/healthcheck-redis
```

## Configuration

You can publish the config file with:

```shell
php artisan vendor:publish --tag="healthcheck-redis-config"
```

The `healthcheck-redis.php` config file allows you to define whether the check is strictly required by default. 

```php
return [
    // If true, the check will FAIL when the extension is missing.
    // If false, it will generate a WARNING instead.
    'required' => false,
];
```

## Usage & Integration

Register the check inside your application's health service provider (e.g. `AppServiceProvider` or a dedicated `HealthServiceProvider`), alongside your other Spatie Laravel Health checks:

### Basic Registration

```php
use IllumaLaw\HealthCheckRedis\RedisExtensionCheck;
use Spatie\Health\Facades\Health;

Health::checks([
    RedisExtensionCheck::new(),
]);
```

### Fluent Configuration

You can override the config file's default strictness on a per-check basis using the fluent `required()` method. 

```php
use IllumaLaw\HealthCheckRedis\RedisExtensionCheck;
use Spatie\Health\Facades\Health;

Health::checks([
    // Make the health check FAIL immediately if redis is missing
    RedisExtensionCheck::new()->required(true),
]);
```

### Expected Result States

The check interacts with the Spatie Health dashboard and JSON endpoints using these states:

- **Ok:** The redis extension is installed. The short summary and meta data will include the exact installed version (e.g. `0.7.0`).
- **Warning:** redis is missing, but `required` is set to `false`.
- **Failed:** redis is missing and `required` is set to `true`.
- **Failed (Exception):** The database query to `pg_extension` throws an exception (e.g., database connection down).

## Testing

Run the test suite:

```shell
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

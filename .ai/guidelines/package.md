---
description: Redis extension and memory health check for Spatie Laravel Health
---

# healthcheck-redis

Redis extension and memory health check for `spatie/laravel-health`. Verifies the PHP `redis` extension is present and the Redis instance is within safe memory limits.

## Namespace

`IllumaLaw\HealthCheckRedis`

## Key Check

- `RedisMemoryCheck` — checks PHP extension presence + Redis `maxmemory` usage

## Registration

```php
use IllumaLaw\HealthCheckRedis\RedisMemoryCheck;
use Spatie\Health\Facades\Health;

Health::checks([
    RedisMemoryCheck::new()
        ->required(true), // true = FAIL if over limit; false = WARNING
]);
```

## Notes

- Reports the PHP `redis` extension version in health meta data.
- Monitors memory usage against `maxmemory` (or a custom threshold).
- Choose `required(false)` for degraded-but-operational memory situations (warning only).

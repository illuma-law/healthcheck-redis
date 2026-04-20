<?php

declare(strict_types=1);

namespace IllumaLaw\HealthCheckRedis;

use Illuminate\Support\Facades\Redis;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;
use Throwable;

class RedisMemoryCheck extends Check
{
    protected string $connectionName = 'default';

    public function connectionName(string $connectionName): self
    {
        $this->connectionName = $connectionName;

        return $this;
    }

    public function run(): Result
    {
        $baseMeta = ['connection_name' => $this->connectionName];

        try {
            $connection = Redis::connection($this->connectionName);
            $ping = $connection->ping();
        } catch (Throwable $exception) {
            return Result::make()
                ->meta($baseMeta)
                ->failed("An exception occurred when connecting to Redis: `{$exception->getMessage()}`");
        }

        if ($ping === false) {
            return Result::make()
                ->meta($baseMeta)
                ->failed('Redis returned a falsy response when trying to connect to it.');
        }

        try {
            /** @var array<string, mixed>|false $memory */
            $memory = $connection->info('memory');
        } catch (Throwable $exception) {
            return Result::make()
                ->meta($baseMeta)
                ->failed("Redis ping succeeded but INFO memory failed: {$exception->getMessage()}");
        }

        if (! is_array($memory) || $memory === []) {
            return Result::make()
                ->meta($baseMeta)
                ->ok('Redis is reachable.')
                ->shortSummary('Ping OK');
        }

        $usedHuman = isset($memory['used_memory_human']) ? (string) $memory['used_memory_human'] : '';
        $maxHuman = isset($memory['maxmemory_human']) ? (string) $memory['maxmemory_human'] : '';
        $usedBytes = isset($memory['used_memory']) ? (int) $memory['used_memory'] : 0;
        $maxBytes = isset($memory['maxmemory']) ? (int) $memory['maxmemory'] : 0;

        $usagePercent = null;
        if ($maxBytes > 0) {
            $usagePercent = round(($usedBytes / $maxBytes) * 100, 1);
        }

        $meta = $baseMeta;

        if ($usedHuman !== '') {
            $meta['used_memory_human'] = $usedHuman;
        }

        if ($maxHuman !== '') {
            $meta['maxmemory_human'] = $maxHuman;
        }

        if ($usagePercent !== null) {
            $meta['memory_usage_percent'] = $usagePercent;
        }

        $summary = $usedHuman !== '' ? $usedHuman : 'Ping OK';
        if ($usagePercent !== null) {
            $summary .= " ({$usagePercent}% of max)";
        }

        return Result::make()
            ->meta($meta)
            ->ok('Redis is reachable.')
            ->shortSummary($summary);
    }
}

<?php

declare(strict_types=1);

use IllumaLaw\HealthCheckRedis\RedisExtensionCheck;
use IllumaLaw\HealthCheckRedis\RedisMemoryCheck;
use Illuminate\Support\Facades\Redis;
use Spatie\Health\Enums\Status;

it('succeeds when redis extension is installed', function () {
    if (! extension_loaded('redis')) {
        $this->markTestSkipped('Redis extension not installed.');
    }

    $result = RedisExtensionCheck::new()->run();

    expect($result->status)->toEqual(Status::ok())
        ->and($result->meta)->toHaveKey('installed_version');
});

it('succeeds when redis is reachable', function () {
    $mockConnection = Mockery::mock();
    $mockConnection->shouldReceive('ping')->once()->andReturn(true);
    $mockConnection->shouldReceive('info')->once()->with('memory')->andReturn([
        'used_memory_human' => '1.23M',
        'maxmemory_human' => '100M',
        'used_memory' => 1230000,
        'maxmemory' => 100000000,
    ]);

    Redis::shouldReceive('connection')->with('default')->once()->andReturn($mockConnection);

    $result = RedisMemoryCheck::new()->run();

    expect($result->status)->toEqual(Status::ok())
        ->and($result->notificationMessage)->toBe('Redis is reachable.')
        ->and($result->shortSummary)->toContain('1.23M');
});

it('fails when redis is unreachable', function () {
    Redis::shouldReceive('connection')->with('default')->once()->andThrow(new Exception('Connection failed'));

    $result = RedisMemoryCheck::new()->run();

    expect($result->status)->toEqual(Status::failed())
        ->and($result->notificationMessage)->toContain('An exception occurred when connecting to Redis');
});

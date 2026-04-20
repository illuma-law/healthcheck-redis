<?php

declare(strict_types=1);

use IllumaLaw\HealthCheckPgvector\PgvectorExtensionCheck;
use IllumaLaw\HealthCheckPgvector\Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Spatie\Health\Enums\Status;

uses(TestCase::class);

it('succeeds when pgvector is installed', function () {
    DB::shouldReceive('selectOne')
        ->once()
        ->with("select extversion as version from pg_extension where extname = 'vector' limit 1")
        ->andReturn((object) ['version' => '0.5.0']);

    $result = PgvectorExtensionCheck::new()->run();

    expect($result->status)->toEqual(Status::ok())
        ->and($result->meta['installed_version'])->toBe('0.5.0');
});

it('fails when pgvector is required but not installed', function () {
    DB::shouldReceive('selectOne')
        ->once()
        ->andReturn(null);

    $result = PgvectorExtensionCheck::new()
        ->required(true)
        ->run();

    expect($result->status)->toEqual(Status::failed())
        ->and($result->shortSummary)->toBe('Missing');
});

it('warns when pgvector is not required and not installed', function () {
    DB::shouldReceive('selectOne')
        ->once()
        ->andReturn(null);

    $result = PgvectorExtensionCheck::new()
        ->required(false)
        ->run();

    expect($result->status)->toEqual(Status::warning())
        ->and($result->shortSummary)->toBe('Not installed');
});

it('fails when query throws exception', function () {
    DB::shouldReceive('selectOne')
        ->once()
        ->andThrow(new Exception('Database error'));

    $result = PgvectorExtensionCheck::new()->run();

    expect($result->status)->toEqual(Status::failed())
        ->and($result->notificationMessage)->toContain('Database error');
});

it('uses configuration fallback for required state', function () {
    DB::shouldReceive('selectOne')->andReturn(null);
    config()->set('healthcheck-pgvector.required', true);

    $result = PgvectorExtensionCheck::new()->run();

    expect($result->status)->toEqual(Status::failed())
        ->and($result->shortSummary)->toBe('Missing');
});

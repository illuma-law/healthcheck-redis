<?php

declare(strict_types=1);

namespace IllumaLaw\HealthCheckPgvector\Tests;

use IllumaLaw\HealthCheckPgvector\PgvectorVitalsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Health\HealthServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            HealthServiceProvider::class,
            PgvectorVitalsServiceProvider::class,
        ];
    }
}

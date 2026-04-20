<?php

declare(strict_types=1);

namespace IllumaLaw\HealthCheckRedis;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class HealthcheckRedisServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('healthcheck-redis')
            ->hasConfigFile()
            ->hasTranslations();
    }
}

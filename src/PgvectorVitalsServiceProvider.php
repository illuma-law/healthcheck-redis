<?php

declare(strict_types=1);

namespace IllumaLaw\HealthCheckPgvector;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class PgvectorVitalsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('healthcheck-pgvector')
            ->hasConfigFile()
            ->hasTranslations();
    }
}

<?php

declare(strict_types=1);

namespace IllumaLaw\HealthCheckRedis;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class RedisExtensionCheck extends Check
{
    protected ?bool $required = null;

    public function required(bool $required = true): self
    {
        $this->required = $required;

        return $this;
    }

    public function run(): Result
    {
        $required = $this->required ?? config('healthcheck-redis.required', false);

        $extensionInstalled = extension_loaded('redis');

        if ($extensionInstalled) {
            $version = phpversion('redis');

            return Result::make()
                ->ok()
                ->shortSummary($version ?: 'Installed')
                ->meta(['installed_version' => $version])
                ->notificationMessage(trans('healthcheck-redis::messages.installed', ['version' => $version]));
        }

        if ($required) {
            return Result::make()
                ->failed(trans('healthcheck-redis::messages.missing'))
                ->shortSummary('Missing');
        }

        return Result::make()
            ->warning(trans('healthcheck-redis::messages.not_installed'))
            ->shortSummary('Not installed');
    }
}

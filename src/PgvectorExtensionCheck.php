<?php

declare(strict_types=1);

namespace IllumaLaw\HealthCheckPgvector;

use Illuminate\Support\Facades\DB;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;
use Throwable;

final class PgvectorExtensionCheck extends Check
{
    private ?bool $required = null;

    public function required(bool $required = true): self
    {
        $this->required = $required;

        return $this;
    }

    public function run(): Result
    {
        $result = Result::make();

        try {
            $row = DB::selectOne("select extversion as version from pg_extension where extname = 'vector' limit 1");
        } catch (Throwable $exception) {
            return $result
                ->failed(__('healthcheck-pgvector::messages.query_failed', ['message' => $exception->getMessage()]))
                ->shortSummary('Query failed');
        }

        $version = is_object($row) && isset($row->version) ? (string) $row->version : null;

        if ($version !== null && $version !== '') {
            return $result
                ->ok(__('healthcheck-pgvector::messages.installed', ['version' => $version]))
                ->shortSummary($version)
                ->meta(['installed_version' => $version]);
        }

        $isRequired = $this->required ?? (bool) config('healthcheck-pgvector.required', false);

        if ($isRequired) {
            return $result->failed(__('healthcheck-pgvector::messages.missing'))->shortSummary('Missing');
        }

        return $result->warning(__('healthcheck-pgvector::messages.not_installed'))->shortSummary('Not installed');
    }
}

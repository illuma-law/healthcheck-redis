<?php

declare(strict_types=1);

return [
    /*
     * Whether the redis extension is required.
     * If true, the check will fail if the extension is not installed.
     * If false, it will only result in a warning.
     */
    'required' => env('HEALTH_REDIS_REQUIRED', false),
];

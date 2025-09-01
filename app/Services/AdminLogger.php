<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AdminLogger
{
    public static function error(string $message, \Throwable $e = null): void
    {
        Log::channel('admin')->error($message, [
            'error' => $e?->getMessage(),
            'admin_id' => auth()->id(),
            'timestamp' => now(),
        ]);
    }

    public static function info(string $message, array $context = []): void
    {
        Log::channel('admin')->info($message, array_merge($context, [
            'admin_id' => auth()->id(),
            'timestamp' => now(),
        ]));
    }
}

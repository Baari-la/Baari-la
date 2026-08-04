<?php

namespace App\Support;

class Debug
{
    public static function log(string $message, array $context = []): void
    {
        if (! config('app.debug_log')) {
            return;
        }

        logger()->info($message, $context);
    }
}
<?php

namespace App\Ignition;

use ErrorException;
use Spatie\Ignition\Ignition;

class WordpressIgnition extends Ignition
{
    public function renderError(
        int $level,
        string $message,
        string $file = '',
        int $line = 0,
        array $context = [],
    ): void {
        // Relax error reporting because wordpress
        if (in_array($level, [E_USER_NOTICE, E_NOTICE, E_DEPRECATED, E_USER_DEPRECATED, E_WARNING])) {
            return;
        }

        throw new ErrorException($message, 0, $level, $file, $line);
    }
}

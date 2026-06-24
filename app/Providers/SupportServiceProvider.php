<?php

namespace App\Providers;

final class SupportServiceProvider
{
    public function boot(): void
    {
        add_action('after_setup_theme', function () {
            foreach (config('supports', []) as $key => $value) {
                if (is_array($value)) {
                    add_theme_support($key, $value);
                } else {
                    add_theme_support($value);
                }
            }
        });
    }
}

<?php

namespace App\Providers;

use Timber\Timber;

final class BlocksServiceProvider
{
    public function boot(): void
    {
        add_action('init', $this->init(...));
    }

    protected function init()
    {
        foreach (config('blocks') as $key => $block) {
            $block['render_callback'] = fn ($attributes) => $this->render($key, $attributes);
            register_block_type('starter/'.$key, $block);
        }
    }

    protected function render(string $key, array $attributes)
    {
        return Timber::compile("/_blocks/$key.twig", $attributes);
    }
}

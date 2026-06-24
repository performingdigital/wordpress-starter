<?php

namespace App\Providers;

class ViteServiceProvider
{
    protected array $config = [];

    public function boot(): void
    {
        $config = config('vite', []);

        if (!is_array($config) || ($config['enabled'] ?? false) !== true) {
            return;
        }

        $this->config = $config;

        add_action(
            'wp_head',
            function () {
                echo '<!-- Vite Assets -->' . "\n";
                echo $this->preload() . "\n";
                echo $this->scripts() . "\n";
                echo $this->styles() . "\n";
                echo '<!-- /Vite Assets -->' . "\n";
            },
            999,
        );
    }

    public function preload(): string
    {
        if ($this->config['useDevServer'] ?? false) {
            return '';
        }

        $manifest = $this->manifest();
        $chunk = $manifest[$this->config['entry']] ?? null;

        if (!$chunk) {
            return '';
        }

        $tags = [];

        foreach ($chunk['imports'] ?? [] as $import) {
            $file = $manifest[$import]['file'] ?? null;

            if ($file) {
                $tags[] = $this->makePreloadTag($this->buildUrl($file));
            }
        }

        return implode('', $tags);
    }

    public function scripts(): string
    {
        if ($this->config['useDevServer'] ?? false) {
            return implode('', [
                $this->makeScriptTag(rtrim($this->config['devServer'], '/') . '/@vite/client'),
                $this->makeScriptTag(rtrim($this->config['devServer'], '/') . '/' . ltrim($this->config['entry'], '/')),
            ]);
        }

        $file = $this->manifest()[$this->config['entry']]['file'] ?? null;

        return $file ? $this->makeScriptTag($this->buildUrl($file)) : '';
    }

    public function styles(): string
    {
        if ($this->config['useDevServer'] ?? false) {
            return '';
        }

        return implode('', array_map(
            fn (string $url) => $this->makeStyleTag($url),
            $this->getStylesUrls(),
        ));
    }

    public function getStylesUrls(): array
    {
        $chunk = $this->manifest()[$this->config['entry']] ?? null;

        if (!$chunk) {
            return [];
        }

        return array_map(
            fn (string $css) => $this->buildUrl($css),
            $chunk['css'] ?? [],
        );
    }

    protected function makeScriptTag(?string $url = null): string
    {
        return $url ? "<script type='module' crossorigin src='{$url}'></script>" : '';
    }

    protected function makePreloadTag(?string $url = null): string
    {
        return $url ? "<link rel='modulepreload' href='{$url}'>" : '';
    }

    protected function makeStyleTag(?string $url = null): string
    {
        return $url ? "<link rel='stylesheet' href='{$url}'>" : '';
    }

    protected function buildUrl(string $file): string
    {
        return rtrim((string) env('WP_HOME'), '/') . '/' . trim($this->config['build'], '/') . '/' . ltrim($file, '/');
    }

    protected function manifest(): array
    {
        $path = $this->config['manifestPath'] ?? null;

        if (!$path || !file_exists($path)) {
            return [];
        }

        return json_decode((string) file_get_contents($path), true) ?: [];
    }
}

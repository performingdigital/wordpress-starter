<?php

namespace App\Providers;

final class ViteServiceProvider
{
    protected array $config = [];

    public function boot(): void
    {
        $this->config = config('vite');

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

    protected function preload(): string
    {
        if ($this->config['useDevServer']) {
            return '';
        }

        $manifest = $this->manifest();
        $chunk = $manifest[$this->config['entry']];
        $tags = [];

        foreach ($chunk['imports'] ?? [] as $import) {
            foreach ($manifest[$import]['file'] ?? [] as $file) {
                if ($file) {
                    $url = $this->buildUrl($file);
                    $tags[] = $this->makePreloadTag($url);
                }
            }
        }

        return implode('', $tags);
    }

    protected function scripts(): string
    {
        $tags = [];

        if ($this->config['useDevServer']) {
            $tags[] = $this->makeScriptTag('http://localhost:5173/@vite/client');
            $tags[] = $this->makeScriptTag('http://localhost:5173/' . $this->config['entry']);
        } else {
            $manifest = collect($this->manifest());
            if ($manifest->has($this->config['entry']) && collect($manifest[$this->config['entry']])->has('file')) {
                $url = $this->buildUrl($this->manifest()[$this->config['entry']]['file']);
                $tags[] = $this->makeScriptTag($url);
            }
        }

        return implode('', $tags);
    }

    protected function styles(): string
    {
        if ($this->config['useDevServer']) {
            return '';
        }

        $tags = [];

        foreach ($this->getStylesUrls() as $url) {
            $tags[] = $this->makeStyleTag($url);
        }

        return implode('', $tags);
    }

    protected function getStylesUrls()
    {
        $manifest = $this->manifest();
        $chunk = $manifest[$this->config['entry']];
        $urls = [];

        foreach ($chunk['css'] ?? [] as $css) {
            if ($css) {
                $urls[] = $this->buildUrl($css);
            }
        }

        return $urls;
    }

    protected function makeScriptTag(string $url = null)
    {
        if (is_null($url)) {
            return '';
        }

        return "<script type='module' crossorigin src='" . $url . "'></script>";
    }

    protected function makePreloadTag(string $url = null)
    {
        if (is_null($url)) {
            return '';
        }

        return "<link rel='modulepreload' href='" . $url . "'>";
    }

    protected function makeStyleTag(string $url = null)
    {
        if (is_null($url)) {
            return '';
        }

        return "<link rel='stylesheet' href='" . $url . "'>";
    }

    protected function buildUrl(string $file): string
    {
        return '/dist/' . $file;
    }

    protected function manifest(): ?array
    {
        try {
            return json_decode(json: file_get_contents($this->config['manifestPath']), associative: true);
        } catch (\Throwable $e) {
            throw new \Exception('manifest.json not found');
        }
    }
}

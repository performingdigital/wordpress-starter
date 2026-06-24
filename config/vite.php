<?php

return [
    /**
     * @var bool Should the dev server be used for?
     */
    'useDevServer' => env('WP_DEV_SERVER'),

    /**
     * @var string File system path (or URL) to the Vite-built manifest.json
     */
    'manifestPath' => ROOT_DIR . '/public/dist/manifest.json',

    /**
     * @var string The JavaScript entry from the manifest.json to inject on Twig error pages
     *              This can be a string or an array of strings
     */
    'entry' => 'resources/js/app.ts',

    /**
     * @var string The output directory of the build files.
     */
    'build' => '/public/dist/',
];

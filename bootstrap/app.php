<?php

use Illuminate\Container\Container;
use Illuminate\Support\Arr;

require_once ROOT_DIR . '/vendor/autoload.php';

Container::setInstance($app = new Container());
$app->instance(Container::class, $app);
$app->instance('path', ROOT_DIR);

function app(?string $abstract = null): mixed
{
    $app = Container::getInstance();

    return $abstract ? $app->make($abstract) : $app;
}

function config(?string $key = null, mixed $default = null): mixed
{
    static $items = [];

    if ($key === null) {
        return $items;
    }

    [$file, $path] = array_pad(explode('.', $key, 2), 2, null);

    if (!array_key_exists($file, $items)) {
        $value = file_exists($config = ROOT_DIR . "/config/{$file}.php") ? require $config : [];
        $items[$file] = is_array($value) ? $value : [];
    }

    return $path ? Arr::get($items[$file], $path, $default) : $items[$file];
}


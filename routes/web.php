<?php

use Timber\Timber;

$context = Timber::context();

if (is_singular()) {
    $context['post'] = Timber::get_post();
}

$template = match (true) {
    is_front_page() => 'front-page.twig',
    is_home() => 'home.twig',
    is_page() => 'page.twig',
    is_single() => 'single.twig',
    is_archive() => 'archive.twig',
    is_search() => 'search.twig',
    is_404() => '404.twig',
    default => 'index.twig',
};

Timber::render(
    [
        $template,
        'index.twig',
    ],
    $context,
);

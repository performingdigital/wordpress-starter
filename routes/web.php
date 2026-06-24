<?php

use Timber\Timber;

$context = Timber::context();

if (app()->bound('starter.plugin_template')) {
    app()->instance('starter.capturing_plugin_template', true);

    ob_start();
    include app('starter.plugin_template');
    $context['content'] = ob_get_clean();

    app()->forgetInstance('starter.capturing_plugin_template');

    Timber::render('plugin.twig', $context);
    return;
}

if (is_singular()) {
    $context['post'] = Timber::get_post();
}

$template = match (true) {
    function_exists('is_account_page') && is_account_page() => 'woocommerce/my-account.twig',
    is_woocommerce() => 'woocommerce.twig',
    is_front_page() => 'front-page.twig',
    is_home() => 'home.twig',
    is_page() => 'page.twig',
    is_single() => 'single.twig',
    is_archive() => 'archive.twig',
    is_search() => 'search.twig',
    is_404() => '404.twig',
    default => 'index.twig',
};

Timber::render([$template, 'index.twig'], $context);

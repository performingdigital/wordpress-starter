<?php
/**
 * Plugin Name: Project Application Bootstrap
 * Description: Project-level MU plugin loaded outside Composer-managed WordPress core.
 */

if (!defined('ABSPATH')) {
    exit;
}

$theme = 'starter';

add_filter('pre_option_template', function () use ($theme) {
    return $theme;
});

add_filter('pre_option_stylesheet', function () use ($theme) {
    return $theme;
});

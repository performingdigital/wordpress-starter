<?php

define('ROOT_DIR', dirname(__DIR__));
define('PUBLIC_DIR', ROOT_DIR . '/public');

/** Fix permissions */
define('FS_CHMOD_DIR', (0755 & ~ umask()));
define('FS_CHMOD_FILE', (0644 & ~ umask()));

/** Bootstrap the application container. */
require_once ROOT_DIR . '/bootstrap/app.php';

/**  Initialize Timber. */
Timber\Timber::init();
Timber\Timber::$dirname = 'templates';
Timber\Timber::$locations = [ROOT_DIR . '/templates'];

/** Load env file */
if (class_exists('Dotenv\Dotenv') && file_exists(ROOT_DIR . '/.env')) {
    $vars = Dotenv\Dotenv::createImmutable(ROOT_DIR)->load();

    foreach (array_keys($vars) as $key) {
        !defined($key) && define($key, env($key));
    }
}

/** WordPress core directory */
defined('ABSPATH') || define('ABSPATH', PUBLIC_DIR . '/wordpress/');

/** Public URLs */
if (!defined('WP_SITEURL') && defined('WP_HOME')) {
    define('WP_SITEURL', rtrim(WP_HOME, '/') . '/wordpress');
}

/**
 * WordPress content directory.
 *
 * Keep this outside public/wordpress so Composer can reinstall WordPress core
 * without deleting custom themes, MU plugins, uploads, or Composer-installed plugins.
 */
defined('WP_CONTENT_DIR') || define('WP_CONTENT_DIR', PUBLIC_DIR . '/content');

defined('WP_PLUGIN_DIR') || define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
defined('WPMU_PLUGIN_DIR') || define('WPMU_PLUGIN_DIR', WP_CONTENT_DIR . '/mu-plugins');

if (!defined('WP_CONTENT_URL') && defined('WP_HOME')) {
    define('WP_CONTENT_URL', rtrim(WP_HOME, '/') . '/content');
}

defined('WP_PLUGIN_URL') || define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
defined('WPMU_PLUGIN_URL') || define('WPMU_PLUGIN_URL', WP_CONTENT_URL . '/mu-plugins');

/** Debug */
if (class_exists(\App\Ignition\WordpressIgnition::class) && env('WP_ENV') === 'dev' ) {
    (new \App\Ignition\WordpressIgnition())->register();
}

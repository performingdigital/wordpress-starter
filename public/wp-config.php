<?php

require_once dirname(__DIR__) . '/bootstrap/wordpress.php';

$table_prefix = defined('DB_PREFIX') ? DB_PREFIX : 'wp_';

require_once ABSPATH . 'wp-settings.php';

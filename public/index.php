<?php

define('WP_USE_THEMES', true);

require __DIR__ . '/wp-config.php';
wp();
require ABSPATH . WPINC . '/template-loader.php';

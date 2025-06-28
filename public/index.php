<?php

/**
 * Simple PHP MVC Framework
 *
 * Entry point for the application
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load configuration
require_once BASE_PATH . '/config/config.php';

// Load system core
require_once BASE_PATH . '/system/Bootstrap.php';

// Initialize the application
$app = new \System\Bootstrap();
$app->run();

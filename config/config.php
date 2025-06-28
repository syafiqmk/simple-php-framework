<?php

/**
 * Configuration file
 *
 * Contains all application configurations
 */

// Application configuration
define('APP_NAME', 'Simple PHP MVC Framework');
define('APP_URL', 'http://localhost');
define('APP_DEBUG', true);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'mvc_database');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Paths
define('CONTROLLER_PATH', BASE_PATH . '/app/controllers/');
define('MODEL_PATH', BASE_PATH . '/app/models/');
define('VIEW_PATH', BASE_PATH . '/app/views/');
define('SYSTEM_PATH', BASE_PATH . '/system/');

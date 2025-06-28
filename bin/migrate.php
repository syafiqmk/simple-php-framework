#!/usr/bin/env php
<?php

/**
 * Simple PHP MVC Framework
 *
 * CLI tool for migrations
 */

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    exit('This script can only be run from the command line.');
}

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load configuration
require_once BASE_PATH . '/config/config.php';

// Load migration class
require_once BASE_PATH . '/system/Database/Migration.php';

// Parse command line arguments
$command = $argv[1] ?? 'help';
$param = $argv[2] ?? '';

// Create migration instance
$migration = new \System\Database\Migration();

// Process command
switch ($command) {
    case 'run':
        echo "Running migrations...\n";
        $migration->run();
        break;

    case 'reset':
        echo "Resetting all migrations...\n";
        $migration->reset();
        break;

    case 'rollback':
        $steps = is_numeric($param) ? (int)$param : 1;
        echo "Rolling back {$steps} migration(s)...\n";
        $migration->rollback($steps);
        break;

    case 'create':
        if (empty($param)) {
            echo "Error: Migration name is required.\n";
            echo "Usage: php bin/migrate.php create <migration_name>\n";
            exit(1);
        }
        $migration->create($param);
        break;

    case 'help':
    default:
        echo "Simple PHP MVC Framework Migration Tool\n\n";
        echo "Usage:\n";
        echo "  php bin/migrate.php run                    Run all pending migrations\n";
        echo "  php bin/migrate.php reset                  Rollback all migrations\n";
        echo "  php bin/migrate.php rollback [steps]       Rollback the last migration or specified number of migrations\n";
        echo "  php bin/migrate.php create <name>          Create a new migration\n";
        echo "  php bin/migrate.php help                   Display this help message\n";
        break;
}

echo "\nDone.\n";

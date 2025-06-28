#!/usr/bin/env php
<?php

/**
 * Simple PHP MVC Framework
 *
 * Development server runner
 * 
 * Usage: php bin/serve.php [port] [host]
 * Example: php bin/serve.php 8080 0.0.0.0
 */

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    exit('This script can only be run from the command line.');
}

// Default values
$port = $argv[1] ?? 8000;
$host = $argv[2] ?? 'localhost';

// Start development server
$command = sprintf(
    'php -S %s:%d -t %s/public',
    $host,
    $port,
    dirname(__DIR__)
);

echo "Starting development server on http://{$host}:{$port}\n";
echo "Document root: " . dirname(__DIR__) . "/public\n";
echo "Press Ctrl+C to stop the server\n\n";

passthru($command);

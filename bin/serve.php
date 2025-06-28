#!/usr/bin/env php
<?php

/**
 * Simple PHP MVC Framework
 *
 * Development server runner
 */

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    exit('This script can only be run from the command line.');
}

// Default port
$port = $argv[1] ?? 8000;

// Start development server
$command = sprintf(
    'php -S localhost:%d -t %s/public',
    $port,
    dirname(__DIR__)
);

echo "Starting development server on http://localhost:{$port}\n";
echo "Press Ctrl+C to stop the server\n\n";

passthru($command);

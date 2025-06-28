<?php

/**
 * Simple PHP Framework Test Runner
 * 
 * This script runs tests for the framework components.
 * Usage: php bin/test.php [component]
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Display header
echo "\n";
echo "=======================================\n";
echo "  Simple PHP Framework - Test Runner   \n";
echo "=======================================\n\n";

// Parse arguments
$component = $argv[1] ?? 'all';

// Define test components
$components = [
    'router' => 'Testing routing system',
    'view' => 'Testing view engine',
    'session' => 'Testing session management',
    'middleware' => 'Testing middleware system',
];

// Run specific component test or all tests
if ($component === 'all') {
    // Run all tests
    foreach ($components as $name => $description) {
        runTest($name, $description);
    }
} elseif (array_key_exists($component, $components)) {
    // Run specific test
    runTest($component, $components[$component]);
} else {
    echo "Error: Unknown component '{$component}'\n";
    echo "Available components: " . implode(', ', array_keys($components)) . ", all\n";
    exit(1);
}

echo "\nAll tests completed.\n\n";

/**
 * Run a specific test
 * 
 * @param string $component Component name
 * @param string $description Test description
 * @return void
 */
function runTest($component, $description)
{
    echo "Running: {$description}...\n";

    $testFile = BASE_PATH . "/tests/{$component}Test.php";

    if (file_exists($testFile)) {
        require_once $testFile;
        echo "✅ {$component} tests passed\n\n";
    } else {
        echo "⚠️  Test file not found: {$testFile}\n\n";
    }
}

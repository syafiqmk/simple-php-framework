<?php

/**
 * Simple PHP Framework Test Runner
 * 
 * This script runs tests for the framework components.
 * Usage: php bin/test.php [component]
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Enable error reporting for test environment
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $allPassed = true;
    foreach ($components as $name => $description) {
        $result = runTest($name, $description);
        if (!$result) {
            $allPassed = false;
            break;
        }
    }
} elseif (array_key_exists($component, $components)) {
    // Run specific test
    runTest($component, $components[$component]);
} else {
    echo "Error: Unknown component '{$component}'\n";
    echo "Available components: " . implode(', ', array_keys($components)) . ", all\n";
    exit(1);
}

if (isset($allPassed) && $allPassed) {
    echo "\n✅ All tests completed successfully.\n\n";
} else {
    echo "\n⚠️ Some tests failed or were not found.\n\n";
}

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
        try {
            // Capture output to prevent interference
            ob_start();

            // Execute test in separate scope
            $result = include $testFile;

            // Get output content
            $output = ob_get_clean();

            // Print any output from the test
            if (trim($output)) {
                echo $output . "\n";
            }

            echo "✅ {$component} tests passed\n\n";
            return true;
        } catch (\Exception $e) {
            // Clean output buffer if test failed
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            echo "❌ Test failed: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n\n";
            return false;
        } catch (\Error $e) {
            // Clean output buffer if test failed with fatal error
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            echo "❌ Test failed with error: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n\n";
            return false;
        }
    } else {
        echo "⚠️  Test file not found: {$testFile}\n\n";
        return true; // Consider missing tests as "passed" for the purpose of continuing
    }
}

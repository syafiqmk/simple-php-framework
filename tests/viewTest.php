<?php

/**
 * View Engine Test
 * 
 * Tests for the template engine
 */

// Load necessary files
require_once dirname(__DIR__) . '/system/View/Engine.php';
require_once dirname(__DIR__) . '/system/View.php';

use System\View\Engine;

// Create temporary test view file
$testViewDir = dirname(__DIR__) . '/tests/views';
if (!is_dir($testViewDir)) {
    mkdir($testViewDir, 0755, true);
}

$testView = $testViewDir . '/test.php';
file_put_contents($testView, '<?php echo $message; ?>');

// Create test layout file
$testLayoutDir = dirname(__DIR__) . '/tests/views/layouts';
if (!is_dir($testLayoutDir)) {
    mkdir($testLayoutDir, 0755, true);
}

$testLayout = $testLayoutDir . '/test.php';
file_put_contents($testLayout, '<div><?php echo yield(\'content\'); ?></div>');

// Test basic view rendering
$engine = new Engine();
$engine->setView($testView);
$engine->setData(['message' => 'Hello World']);

$output = $engine->render();
assert($output === 'Hello World', 'Basic view rendering should work');

// Test view with layout
$engine = new Engine();
$engine->setView($testView);
$engine->setLayout($testLayout);
$engine->setData(['message' => '<strong>Hello World</strong>']);

// Test sections
ob_start();
Engine::section('content');
echo "Section Content";
Engine::endSection();
$sectionOutput = Engine::yield('content');
ob_end_clean();

assert($sectionOutput === 'Section Content', 'Sections should work');

// Test HTML escaping
$escaped = Engine::e('<script>alert("XSS")</script>');
assert($escaped === '&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;', 'HTML escaping should work');

// Clean up test files
unlink($testView);
unlink($testLayout);
rmdir($testLayoutDir);
rmdir($testViewDir);

// Success message
echo "View Engine tests passed successfully\n";

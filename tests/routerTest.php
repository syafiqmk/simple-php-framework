<?php

/**
 * Router Test
 * 
 * Tests for the routing system
 */

// Load necessary files
require_once dirname(__DIR__) . '/system/Request.php';
require_once dirname(__DIR__) . '/system/Response.php';
require_once dirname(__DIR__) . '/system/Router.php';
require_once dirname(__DIR__) . '/system/Route.php';

use System\Request;
use System\Response;
use System\Router;
use System\Route;

// Reset Route singleton for testing
Route::reset();

// Test basic route registration
Route::get('test', function () {
    return 'Test route works';
});

// Test route with parameters
Route::get('users/{id}', function ($id) {
    return "User ID: {$id}";
});

// Test named route
Route::get('named-route', function () {
    return 'Named route works';
})->setName('test.named');

// Verify routes are registered
$routes = Route::getRoutes();
assert(count($routes) === 3, 'Should register 3 routes');

// Test route matching
$match = Route::match('GET', 'test');
assert($match !== false, 'Should match basic route');

$match = Route::match('GET', 'users/123');
assert($match !== false, 'Should match route with parameter');
assert($match['params'][0] === '123', 'Should extract parameter');

$match = Route::match('GET', 'nonexistent');
assert($match === false, 'Should not match nonexistent route');

// Test URL generation
$url = Route::url('test.named');
assert($url === '/named-route', 'Should generate correct URL for named route');

// Success message
echo "Router tests passed successfully\n";

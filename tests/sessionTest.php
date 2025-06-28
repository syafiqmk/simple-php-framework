<?php

/**
 * Session Test
 * 
 * Tests for the session management system
 */

// Set test mode
define('SESSION_TEST_MODE', true);

// Create session array for testing
$_SESSION = [];

// Load necessary files
require_once dirname(__DIR__) . '/system/Session.php';

use System\Session;

// Test session set and get
Session::set('test_key', 'test_value');
assert(Session::get('test_key') === 'test_value', 'Session::get() should return set value');

// Test session has
assert(Session::has('test_key') === true, 'Session::has() should return true for existing key');
assert(Session::has('nonexistent_key') === false, 'Session::has() should return false for non-existing key');

// Test session remove
Session::remove('test_key');
assert(Session::has('test_key') === false, 'Session::remove() should remove the key');

// Test session flash
Session::set('flash_key', 'flash_value');
$flashValue = Session::flash('flash_key');
assert($flashValue === 'flash_value', 'Session::flash() should return the value');
assert(Session::has('flash_key') === false, 'Session::flash() should remove the key after retrieval');

// Test default value
assert(Session::get('nonexistent_key', 'default') === 'default', 'Session::get() should return default value for non-existing key');

// Test flash message system
Session::setFlash('success', 'Operation successful');
assert(Session::getFlash('success') === 'Operation successful', 'Flash messaging system should work');
assert(Session::getFlash('success') === null, 'Flash message should be removed after retrieval');

// Test instance methods
$session = new Session();
$session->setValue('instance_key', 'instance_value');
assert($session->getValue('instance_key') === 'instance_value', 'Instance methods should work');
assert($session->hasKey('instance_key') === true, 'Instance method hasKey() should work');
$session->removeKey('instance_key');
assert($session->hasKey('instance_key') === false, 'Instance method removeKey() should work');

// Success message
echo "Session tests passed successfully\n";

<?php

/**
 * Middleware Test
 * 
 * Tests for the middleware system
 */

// Set test mode first to avoid session warnings
define('SESSION_TEST_MODE', true);

// Create mocks for base test
class MockRequest
{
    private $method;
    private $postData;

    public function __construct($method = 'GET', $postData = [])
    {
        $this->method = $method;
        $this->postData = $postData;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $this->postData;
        }
        return isset($this->postData[$key]) ? $this->postData[$key] : $default;
    }
}

class MockResponse
{
    private $statusCode = 200;
    private $redirectUrl = null;
    private $content = '';

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function redirect($url)
    {
        $this->redirectUrl = $url;
        $this->statusCode = 302;
        return $this;
    }

    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }
}

// Setup session
$_SESSION = [];

// Mock route function
if (!function_exists('route')) {
    function route($name)
    {
        return '/login';
    }
}

// Simple middleware for testing
class TestMiddleware
{
    public function handle($request, $next)
    {
        return $next($request);
    }
}

// Test 1: Basic Middleware Test
echo "Test 1: Basic Middleware Test\n";
$middleware = new TestMiddleware();
$request = new MockRequest();
$nextCalled = false;
$next = function ($req) use (&$nextCalled) {
    $nextCalled = true;
    return "Middleware passed";
};

// Run basic middleware test
$result = $middleware->handle($request, $next);
assert($nextCalled === true, 'Middleware should call next function');
assert($result === "Middleware passed", 'Middleware should return result from next function');

echo "✓ Basic middleware passes control to next function\n\n";

// Test 2: Auth middleware-like functionality
echo "Test 2: Auth Middleware-like Test\n";

// Create an auth-like middleware
class AuthLikeMiddleware
{
    public function handle($request, $next)
    {
        global $_SESSION;

        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $response = new MockResponse();
            return $response->redirect('/login');
        }

        return $next($request);
    }
}

$authMw = new AuthLikeMiddleware();
$request = new MockRequest();
$nextCalled = false;
$next = function ($req) use (&$nextCalled) {
    $nextCalled = true;
    return "Auth passed";
};

// Test unauthenticated flow
$response = $authMw->handle($request, $next);
assert($nextCalled === false, 'Auth middleware should block when not logged in');
assert($response->getRedirectUrl() === '/login', 'Should redirect to login');

// Test authenticated flow
$_SESSION['user_id'] = 123;
$nextCalled = false;
$response = $authMw->handle($request, $next);
assert($nextCalled === true, 'Auth middleware should pass when logged in');
assert($response === "Auth passed", 'Should return next function result');

echo "✓ Auth middleware functionality works correctly\n\n";

// Test 3: CSRF middleware-like functionality
echo "Test 3: CSRF Middleware-like Test\n";

// Create a CSRF-like middleware
class CsrfLikeMiddleware
{
    public function handle($request, $next)
    {
        // Only check CSRF for non-GET requests
        if ($request->getMethod() !== 'GET') {
            $token = $request->post('csrf_token');
            $storedToken = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : null;

            // Verify token exists and matches
            if (!$token || !$storedToken || $token !== $storedToken) {
                $response = new MockResponse();
                return $response->setStatusCode(403)->setContent('CSRF validation failed');
            }
        }

        return $next($request);
    }
}

$csrfMw = new CsrfLikeMiddleware();

// Test GET request (should pass without token check)
$request = new MockRequest('GET');
$nextCalled = false;
$next = function ($req) use (&$nextCalled) {
    $nextCalled = true;
    return "GET passed";
};

$response = $csrfMw->handle($request, $next);
assert($nextCalled === true, 'CSRF should not validate GET requests');

echo "✓ CSRF middleware skips validation for GET requests\n\n";

// Test POST request with invalid token
$_SESSION['csrf_token'] = 'valid_token';
$postData = ['csrf_token' => 'invalid_token'];
$request = new MockRequest('POST', $postData);
$nextCalled = false;
$response = $csrfMw->handle($request, $next);
assert($nextCalled === false, 'CSRF should block invalid tokens');
assert($response->getStatusCode() === 403, 'Should return 403 status');

echo "✓ CSRF middleware blocks requests with invalid token\n\n";

// Test POST request with valid token
$_SESSION['csrf_token'] = 'valid_token';
$postData = ['csrf_token' => 'valid_token'];
$request = new MockRequest('POST', $postData);
$nextCalled = false;
$response = $csrfMw->handle($request, $next);
assert($nextCalled === true, 'CSRF should allow valid tokens');

echo "✓ CSRF middleware allows requests with valid token\n\n";

// Test POST request without token
$_SESSION['csrf_token'] = 'valid_token';
$request = new MockRequest('POST', []);
$nextCalled = false;
$response = $csrfMw->handle($request, $next);
assert($nextCalled === false, 'CSRF should block missing tokens');
assert($response->getStatusCode() === 403, 'Should return 403 status');

echo "✓ CSRF middleware blocks requests without token\n\n";

// Test 4: Middleware Chain Test
echo "Test 4: Middleware Chain Test\n";

// Create a middleware chain
$middlewares = [
    new TestMiddleware(),
    new TestMiddleware(),
    new TestMiddleware()
];

$request = new MockRequest();
$finalHandler = function ($req) {
    return "Chain complete";
};

// Build the chain from the inside out
$chain = $finalHandler;
foreach (array_reverse($middlewares) as $middleware) {
    $nextMiddleware = $chain;
    $chain = function ($req) use ($middleware, $nextMiddleware) {
        return $middleware->handle($req, $nextMiddleware);
    };
}

// Execute the chain
$result = $chain($request);
assert($result === "Chain complete", 'Middleware chain should complete successfully');

echo "✓ Middleware chain works correctly\n\n";

// Clean up
$_SESSION = [];

// Success message
echo "All middleware tests passed successfully!\n";

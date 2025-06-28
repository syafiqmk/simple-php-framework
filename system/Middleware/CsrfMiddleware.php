<?php

namespace System\Middleware;

use System\Request;
use System\Response;
use System\Session;

/**
 * CSRF Middleware Class
 * 
 * Validates CSRF token for POST, PUT, DELETE requests
 */
class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * Handle the middleware
     * 
     * @param Request $request Request instance
     * @param callable $next Next middleware
     * @return mixed
     */
    public function handle($request, $next)
    {
        // Only check CSRF for POST, PUT, DELETE requests
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $token = $request->post('csrf_token');
            $storedToken = Session::get('csrf_token');

            // Verify token exists and matches
            if (!$token || !$storedToken || $token !== $storedToken) {
                $response = new Response();
                return $response->setStatusCode(403)->setContent('CSRF token validation failed');
            }
        }

        // Continue with next middleware/action
        return $next($request);
    }
}

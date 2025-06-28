<?php

namespace System\Middleware;

use System\Request;
use System\Response;
use System\Session;

/**
 * Auth Middleware Class
 * 
 * Checks if user is authenticated before allowing access to a route
 */
class AuthMiddleware implements MiddlewareInterface
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
        // Check if user is authenticated using session
        if (!Session::has('user_id')) {
            $response = new Response();

            // Set flash message
            Session::setFlash('error', 'Please login to access this page');

            // Redirect to login page
            return $response->redirect(route('user.login'));
        }

        // User is authenticated, proceed
        return $next($request);
    }
}

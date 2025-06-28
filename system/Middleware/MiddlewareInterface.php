<?php

namespace System\Middleware;

/**
 * Middleware Interface
 * 
 * All middleware must implement this interface
 */
interface MiddlewareInterface
{
    /**
     * Handle the middleware
     * 
     * @param \System\Request $request Request instance
     * @param callable $next Next middleware
     * @return mixed
     */
    public function handle($request, $next);
}

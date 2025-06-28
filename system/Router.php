<?php

namespace System;

/**
 * Router Class
 *
 * Handles routing requests to appropriate controllers
 */
class Router
{
    /**
     * Routes container
     *
     * @var array
     */
    private $routes = [];

    /**
     * Register a GET route
     *
     * @param string $path
     * @param mixed $callback
     * @return void
     */
    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    /**
     * Register a POST route
     *
     * @param string $path
     * @param mixed $callback
     * @return void
     */
    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    /**
     * Dispatch route
     *
     * @param string $uri
     * @param string $method
     * @return mixed
     */
    public function dispatch($uri, $method)
    {
        $uri = trim($uri, '/');

        // Check if route exists
        if (isset($this->routes[$method][$uri])) {
            $callback = $this->routes[$method][$uri];

            // If callback is string (e.g. 'Controller@method')
            if (is_string($callback)) {
                list($controller, $method) = explode('@', $callback);

                $controllerClass = 'App\\Controllers\\' . $controller;
                $controllerInstance = new $controllerClass();

                return call_user_func([$controllerInstance, $method]);
            }

            // If callback is callable
            if (is_callable($callback)) {
                return call_user_func($callback);
            }
        }

        // Route not found
        header('HTTP/1.1 404 Not Found');
        include VIEW_PATH . 'errors/404.php';
        exit;
    }
}

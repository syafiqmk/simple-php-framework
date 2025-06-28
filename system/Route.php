<?php

namespace System;

/**
 * Route Class
 *
 * Handles advanced routing for the application
 */
class Route
{
    /**
     * Routes collection
     *
     * @var array
     */
    private static $routes = [];

    /**
     * Named routes collection
     * 
     * @var array
     */
    private static $namedRoutes = [];

    /**
     * Current group prefix
     *
     * @var string
     */
    private static $prefix = '';

    /**
     * Current middleware list
     *
     * @var array
     */
    private static $middleware = [];

    /**
     * Current name prefix for route group
     *
     * @var string
     */
    private static $namePrefix = '';

    /**
     * Register a GET route
     *
     * @param string $path
     * @param mixed $callback
     * @return self
     */
    public static function get($path, $callback)
    {
        return self::addRoute('GET', $path, $callback);
    }

    /**
     * Register a POST route
     *
     * @param string $path
     * @param mixed $callback
     * @return self
     */
    public static function post($path, $callback)
    {
        return self::addRoute('POST', $path, $callback);
    }

    /**
     * Register a PUT route
     *
     * @param string $path
     * @param mixed $callback
     * @return self
     */
    public static function put($path, $callback)
    {
        return self::addRoute('PUT', $path, $callback);
    }

    /**
     * Register a DELETE route
     *
     * @param string $path
     * @param mixed $callback
     * @return self
     */
    public static function delete($path, $callback)
    {
        return self::addRoute('DELETE', $path, $callback);
    }

    /**
     * Register a PATCH route
     *
     * @param string $path
     * @param mixed $callback
     * @return self
     */
    public static function patch($path, $callback)
    {
        return self::addRoute('PATCH', $path, $callback);
    }

    /**
     * Register route with any HTTP method
     *
     * @param string $path
     * @param mixed $callback
     * @return self
     */
    public static function any($path, $callback)
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

        foreach ($methods as $method) {
            self::addRoute($method, $path, $callback);
        }

        return new static();
    }

    /**
     * Create a route group with prefix
     *
     * @param string $prefix
     * @param \Closure $callback
     * @return void
     */
    public static function prefix($prefix, \Closure $callback)
    {
        $previousPrefix = self::$prefix;
        self::$prefix = $previousPrefix . '/' . trim($prefix, '/');

        call_user_func($callback);

        self::$prefix = $previousPrefix;

        return new static();
    }

    /**
     * Create a route group with name prefix
     *
     * @param string $name
     * @param \Closure $callback
     * @return void
     */
    public static function name($name, \Closure $callback)
    {
        $previousName = self::$namePrefix;
        self::$namePrefix = $previousName . $name;

        call_user_func($callback);

        self::$namePrefix = $previousName;

        return new static();
    }

    /**
     * Create a route group with middleware
     *
     * @param string|array $middleware
     * @return self
     */
    public static function middleware($middleware)
    {
        if (!is_array($middleware)) {
            $middleware = [$middleware];
        }

        self::$middleware = array_merge(self::$middleware, $middleware);

        return new static();
    }

    /**
     * Execute middleware group
     *
     * @param \Closure $callback
     * @return void
     */
    public static function group(\Closure $callback)
    {
        $previousMiddleware = self::$middleware;

        call_user_func($callback);

        self::$middleware = $previousMiddleware;
    }

    /**
     * Add route to collection
     *
     * @param string $method
     * @param string $path
     * @param mixed $callback
     * @return self
     */
    private static function addRoute($method, $path, $callback)
    {
        // Prepare path with prefix
        $path = self::$prefix . '/' . trim($path, '/');
        $path = str_replace('//', '/', $path);
        $path = trim($path, '/');

        if ($path === '') {
            $path = '/';
        }

        // Add route to collection
        $routeData = [
            'callback' => $callback,
            'middleware' => self::$middleware,
            'name' => null
        ];

        self::$routes[$method][$path] = $routeData;

        return new static();
    }

    /**
     * Name a route
     * 
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $methods = array_keys(self::$routes);

        if (!empty($methods)) {
            $method = end($methods);
            $routes = array_keys(self::$routes[$method]);
            $path = end($routes);

            // Add name prefix if exists
            $fullName = self::$namePrefix . $name;

            // Store the named route
            self::$namedRoutes[$fullName] = [
                'method' => $method,
                'path' => $path
            ];

            // Update route data
            self::$routes[$method][$path]['name'] = $fullName;
        }

        return new static();
    }

    /**
     * Register resource routes (RESTful)
     *
     * @param string $name Base name for routes
     * @param string $controller Controller name
     * @param array $options Options for customizing resource routes
     * @return self
     */
    public static function resource($name, $controller, $options = [])
    {
        $name = trim($name, '/');
        $only = $options['only'] ?? null;
        $except = $options['except'] ?? null;

        $resourceMethods = [
            'index' => ['method' => 'get', 'uri' => $name, 'action' => 'index'],
            'create' => ['method' => 'get', 'uri' => "{$name}/create", 'action' => 'create'],
            'store' => ['method' => 'post', 'uri' => $name, 'action' => 'store'],
            'show' => ['method' => 'get', 'uri' => "{$name}/{id}", 'action' => 'show'],
            'edit' => ['method' => 'get', 'uri' => "{$name}/{id}/edit", 'action' => 'edit'],
            'update' => ['method' => 'put', 'uri' => "{$name}/{id}", 'action' => 'update'],
            'destroy' => ['method' => 'delete', 'uri' => "{$name}/{id}", 'action' => 'destroy']
        ];

        foreach ($resourceMethods as $methodName => $methodData) {
            // Skip if not in 'only' array when specified
            if ($only && !in_array($methodName, $only)) {
                continue;
            }

            // Skip if in 'except' array when specified
            if ($except && in_array($methodName, $except)) {
                continue;
            }

            $method = $methodData['method'];
            $uri = $methodData['uri'];
            $action = $methodData['action'];

            self::$method($uri, "{$controller}@{$action}")->setName("{$name}.{$methodName}");
        }

        return new static();
    }

    /**
     * Generate URL for named route
     *
     * @param string $name
     * @param array $parameters
     * @return string|null
     */
    public static function url($name, $parameters = [])
    {
        if (!isset(self::$namedRoutes[$name])) {
            return null;
        }

        $route = self::$namedRoutes[$name];
        $path = $route['path'];

        // Replace parameters
        foreach ($parameters as $paramName => $paramValue) {
            $path = str_replace("{{$paramName}}", $paramValue, $path);
        }

        // Add base URL if in web context
        if (PHP_SAPI !== 'cli' && isset($_SERVER['HTTP_HOST'])) {
            $baseUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
            $baseUrl .= $_SERVER['HTTP_HOST'];
            return $baseUrl . '/' . $path;
        }

        // Just return path for CLI or test environment
        return '/' . $path;
    }

    /**
     * Get all registered routes
     *
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * Get all named routes
     * 
     * @return array
     */
    public static function getNamedRoutes()
    {
        return self::$namedRoutes;
    }

    /**
     * Load routes from file
     *
     * @param string $file
     * @return void
     */
    public static function loadFromFile($file)
    {
        if (file_exists($file)) {
            require_once $file;
        }
    }

    /**
     * Find route that matches path and method
     *
     * @param string $method
     * @param string $path
     * @return array|null
     */
    public static function match($method, $path)
    {
        // Check if route exists with exact match
        if (isset(self::$routes[$method][$path])) {
            return [
                'route' => self::$routes[$method][$path],
                'params' => []
            ];
        }

        // Check routes with parameters
        foreach (self::$routes[$method] ?? [] as $route => $data) {
            $pattern = self::convertRouteToRegex($route);

            if (preg_match($pattern, $path, $matches)) {
                // Get route parameters
                $params = [];
                preg_match_all('/{([^}]+)}/', $route, $paramNames);

                foreach ($paramNames[1] as $index => $name) {
                    $params[$name] = $matches[$index + 1] ?? null;
                }

                return [
                    'route' => $data,
                    'params' => $params
                ];
            }
        }

        return null;
    }

    /**
     * Convert route pattern to regex
     *
     * @param string $route
     * @return string
     */
    private static function convertRouteToRegex($route)
    {
        $pattern = preg_replace('/\/{([^}]+)}/', '/([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    /**
     * Reset route collections (for testing purposes)
     * 
     * @return void
     */
    public static function reset()
    {
        self::$routes = [];
        self::$namedRoutes = [];
        self::$prefix = '';
        self::$middleware = [];
        self::$namePrefix = '';
    }
}

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
     * Middleware container
     *
     * @var array
     */
    private $middleware = [];

    /**
     * Global middleware
     *
     * @var array
     */
    private $globalMiddleware = [];

    /**
     * Request instance
     *
     * @var Request
     */
    private $request;

    /**
     * Response instance
     *
     * @var Response
     */
    private $response;

    /**
     * Constructor
     *
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request = null, Response $response = null)
    {
        $this->request = $request ?? new Request();
        $this->response = $response ?? new Response();
        $this->loadRoutes();
    }

    /**
     * Load routes from route files
     *
     * @return void
     */
    private function loadRoutes()
    {
        // Load web routes
        $webRoutesFile = BASE_PATH . '/routes/web.php';
        if (file_exists($webRoutesFile)) {
            Route::loadFromFile($webRoutesFile);
        }

        // Load API routes
        $apiRoutesFile = BASE_PATH . '/routes/api.php';
        if (file_exists($apiRoutesFile)) {
            Route::loadFromFile($apiRoutesFile);
        }

        // Get all registered routes
        $this->routes = Route::getRoutes();
    }

    /**
     * Register global middleware
     * 
     * @param string $middlewareClass
     * @return void
     */
    public function registerGlobalMiddleware($middlewareClass)
    {
        $this->globalMiddleware[] = $middlewareClass;
    }

    /**
     * Register route middleware
     * 
     * @param string $name
     * @param string $middlewareClass
     * @return void
     */
    public function registerMiddleware($name, $middlewareClass)
    {
        $this->middleware[$name] = $middlewareClass;
    }

    /**
     * Get middleware by name
     * 
     * @param string $name
     * @return string|null
     */
    public function getMiddleware($name)
    {
        return $this->middleware[$name] ?? null;
    }

    /**
     * Apply middleware to a callback
     * 
     * @param array $middlewareNames
     * @param callable $callback
     * @param Request $request
     * @return mixed
     */
    protected function applyMiddleware($middlewareNames, $callback, $request)
    {
        $middlewareStack = function ($request) use ($callback) {
            return $callback($request);
        };

        // First add global middleware
        $middlewareClasses = [];
        foreach ($this->globalMiddleware as $middlewareClass) {
            $middlewareClasses[] = $middlewareClass;
        }

        // Then add route-specific middleware
        foreach ($middlewareNames as $name) {
            if (isset($this->middleware[$name])) {
                $middlewareClasses[] = $this->middleware[$name];
            }
        }

        // Build middleware stack (in reverse order so the first middleware is the outermost one)
        foreach (array_reverse($middlewareClasses) as $middlewareClass) {
            $middlewareInstance = new $middlewareClass();
            $previousStack = $middlewareStack;
            $middlewareStack = function ($request) use ($middlewareInstance, $previousStack) {
                return $middlewareInstance->handle($request, function ($request) use ($previousStack) {
                    return $previousStack($request);
                });
            };
        }

        // Execute the middleware stack
        return $middlewareStack($request);
    }

    /**
     * Register a GET route
     *
     * @param string $path
     * @param mixed $callback
     * @return void
     */
    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = [
            'callback' => $callback,
            'middleware' => []
        ];
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
        $this->routes['POST'][$path] = [
            'callback' => $callback,
            'middleware' => []
        ];
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
        if ($uri === '') $uri = '/';

        // Find matching route
        $match = Route::match($method, $uri);

        if ($match) {
            $route = $match['route'];
            $params = $match['params'];
            $callback = $route['callback'];

            // Create callback function for the controller and method
            $controllerCallback = function ($request) use ($callback, $route, $params) {
                if (is_string($callback)) {
                    // Handle 'Controller@method' format
                    list($controller, $action) = explode('@', $callback);

                    // Check if controller is in a namespace
                    if (strpos($controller, '\\') === false) {
                        $controllerClass = 'App\\Controllers\\' . $controller;
                    } else {
                        $controllerClass = 'App\\Controllers\\' . $controller;
                    }

                    if (!class_exists($controllerClass)) {
                        return $this->notFound("Controller {$controllerClass} not found");
                    }

                    $controllerInstance = new $controllerClass();

                    // Inject request and response to controller
                    if (method_exists($controllerInstance, 'setRequest')) {
                        $controllerInstance->setRequest($this->request);
                    }

                    if (method_exists($controllerInstance, 'setResponse')) {
                        $controllerInstance->setResponse($this->response);
                    }

                    // Inject session if supported
                    if (method_exists($controllerInstance, 'setSession')) {
                        $controllerInstance->setSession(new \System\Session());
                    }

                    // Check if method exists
                    if (!method_exists($controllerInstance, $action)) {
                        return $this->notFound("Method {$action} not found in {$controllerClass}");
                    }

                    // Execute controller method with parameters
                    return call_user_func_array([$controllerInstance, $action], $params);
                } else {
                    // Execute closure with parameters
                    return call_user_func_array($callback, $params);
                }
            };

            // Apply middleware and execute the callback
            $middlewareNames = $route['middleware'] ?? [];
            $result = $this->applyMiddleware($middlewareNames, $controllerCallback, $this->request);

            return $result;
        }

        // Route not found
        return $this->notFound();
    }

    /**
     * Handle not found routes
     *
     * @param string $message
     * @return void
     */
    private function notFound($message = null)
    {
        if (APP_DEBUG && $message) {
            echo '<h1>Route Error</h1>';
            echo "<p>{$message}</p>";
        } else {
            $this->response->status(404);
            include VIEW_PATH . 'errors/404.php';
        }
        exit;
    }
}

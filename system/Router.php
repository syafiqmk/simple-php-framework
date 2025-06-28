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
     * Middleware handler
     *
     * @var array
     */
    private $middleware = [];

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
     * Register middleware
     *
     * @param string $name
     * @param callable $callback
     * @return void
     */
    public function registerMiddleware($name, $callback)
    {
        $this->middleware[$name] = $callback;
    }

    /**
     * Apply middleware to request
     *
     * @param array $middleware
     * @return bool
     */
    private function applyMiddleware($middleware)
    {
        foreach ($middleware as $name) {
            if (isset($this->middleware[$name])) {
                $result = call_user_func($this->middleware[$name], $this->request, $this->response);

                if ($result === false) {
                    return false;
                }
            }
        }

        return true;
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

            // Apply middleware
            if (!empty($route['middleware'])) {
                $middlewareResult = $this->applyMiddleware($route['middleware']);

                if ($middlewareResult === false) {
                    return $this->response->status(403)->html('Forbidden: Access Denied');
                }
            }

            // Process callback
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

                // Inject request and response to controller if supported
                if (method_exists($controllerInstance, 'setRequest')) {
                    $controllerInstance->setRequest($this->request);
                }

                if (method_exists($controllerInstance, 'setResponse')) {
                    $controllerInstance->setResponse($this->response);
                }

                // Check if method exists
                if (!method_exists($controllerInstance, $action)) {
                    return $this->notFound("Method {$action} not found in {$controllerClass}");
                }

                // Execute controller method with parameters
                return call_user_func_array([$controllerInstance, $action], $params);
            }

            // If callback is a closure
            if (is_callable($callback)) {
                return call_user_func_array($callback, array_values($params));
            }
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

<?php

namespace System;

/**
 * Bootstrap Class
 *
 * The main class that initializes and runs the framework
 */
class Bootstrap
{
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
     * Router instance
     *
     * @var Router
     */
    private $router;

    /**
     * Controller name
     *
     * @var string
     */
    private $controller = 'Home';

    /**
     * Method name
     *
     * @var string
     */
    private $method = 'index';

    /**
     * Parameters
     *
     * @var array
     */
    private $params = [];

    /**
     * Constructor
     *
     * Initialize the application
     */
    public function __construct()
    {
        // Register autoloader
        spl_autoload_register([$this, 'autoload']);

        // Initialize components
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();

        // Start session
        Session::start();

        // Parse URI
        $this->parseUri();
    }

    /**
     * Run the application
     *
     * @return void
     */
    public function run()
    {
        // Check if controller exists
        $controllerFile = CONTROLLER_PATH . $this->controller . 'Controller.php';

        if (file_exists($controllerFile)) {
            // Include the controller file
            require_once $controllerFile;

            // Create controller class name
            $controllerClass = 'App\\Controllers\\' . $this->controller . 'Controller';

            // Check if controller class exists
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();

                // Inject request and response to controller if supported
                if (method_exists($controller, 'setRequest')) {
                    $controller->setRequest($this->request);
                }

                if (method_exists($controller, 'setResponse')) {
                    $controller->setResponse($this->response);
                }

                // Check if method exists
                if (method_exists($controller, $this->method)) {
                    call_user_func_array([$controller, $this->method], $this->params);
                } else {
                    $this->showError('Method ' . $this->method . ' not found in ' . $controllerClass);
                }
            } else {
                $this->showError('Controller ' . $controllerClass . ' not found');
            }
        } else {
            $this->showError('Controller file ' . $controllerFile . ' not found');
        }
    }

    /**
     * Parse URI to determine controller, method, and parameters
     *
     * @return void
     */
    private function parseUri()
    {
        // Get URI from request
        $uri = $this->request->uri();

        // Split URI
        $uriParts = !empty($uri) ? explode('/', $uri) : [];

        // Get controller
        if (!empty($uriParts[0])) {
            $this->controller = ucfirst($uriParts[0]);
            unset($uriParts[0]);
        }

        // Get method
        if (!empty($uriParts[1])) {
            $this->method = $uriParts[1];
            unset($uriParts[1]);
        }

        // Get parameters
        $this->params = !empty($uriParts) ? array_values($uriParts) : [];
    }

    /**
     * Autoloader
     *
     * @param string $className
     * @return void
     */
    private function autoload($className)
    {
        // Convert namespace to file path
        $file = str_replace('\\', '/', $className) . '.php';

        // Build path based on namespace
        if (strpos($className, 'App\\Controllers\\') === 0) {
            $file = BASE_PATH . '/app/controllers/' . str_replace('App/Controllers/', '', $file);
        } elseif (strpos($className, 'App\\Models\\') === 0) {
            $file = BASE_PATH . '/app/models/' . str_replace('App/Models/', '', $file);
        } elseif (strpos($className, 'System\\') === 0) {
            $file = BASE_PATH . '/system/' . str_replace('System/', '', $file);
        }

        // Load file if it exists
        if (file_exists($file)) {
            require_once $file;
        }
    }

    /**
     * Show error
     *
     * @param string $message
     * @return void
     */
    private function showError($message)
    {
        if (APP_DEBUG) {
            echo '<h1>Error</h1>';
            echo '<p>' . $message . '</p>';
        } else {
            header('HTTP/1.1 404 Not Found');
            include VIEW_PATH . 'errors/404.php';
        }
        exit;
    }
}

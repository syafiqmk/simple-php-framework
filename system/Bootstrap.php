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

        // Load helper functions
        $this->loadHelpers();

        // Initialize components
        $this->request = new Request();
        $this->response = new Response();

        // Start session
        Session::start();
    }

    /**
     * Load helper functions
     * 
     * @return void
     */
    private function loadHelpers()
    {
        $helpersFile = BASE_PATH . '/system/helpers.php';
        if (file_exists($helpersFile)) {
            require_once $helpersFile;
        }
    }

    /**
     * Run the application
     *
     * @return void
     */
    public function run()
    {
        try {
            // Create router with request and response
            $this->router = new Router($this->request, $this->response);

            // Register middleware
            $this->registerMiddleware();

            // Dispatch request through router
            $this->router->dispatch($this->request->uri(), $this->request->method());
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Register middleware
     *
     * @return void
     */
    private function registerMiddleware()
    {
        // Register global middleware
        $this->router->registerGlobalMiddleware('System\Middleware\CsrfMiddleware');

        // Register named middleware
        $this->router->registerMiddleware('auth', 'System\Middleware\AuthMiddleware');

        // Register more middleware as needed
    }

    /**
     * Handle exceptions
     *
     * @param \Exception $e
     * @return void
     */
    private function handleException(\Exception $e)
    {
        if (APP_DEBUG) {
            echo '<h1>Error</h1>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        } else {
            $this->response->status(500);
            include VIEW_PATH . 'errors/500.php';
        }
        exit;
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

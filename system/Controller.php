<?php

namespace System;

/**
 * Base Controller Class
 *
 * All controllers should extend this class
 */
class Controller
{
    /**
     * Model instance
     *
     * @var object
     */
    protected $model;

    /**
     * Request instance
     *
     * @var Request
     */
    protected $request;

    /**
     * Response instance
     *
     * @var Response
     */
    protected $response;

    /**
     * Set request object
     *
     * @param Request $request
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Set response object
     *
     * @param Response $response
     * @return void
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Load model
     *
     * @param string $model Model name
     * @return object Model instance
     */
    protected function loadModel($model)
    {
        // Create model class name
        $modelClass = 'App\\Models\\' . $model . 'Model';

        // Check if model file exists
        $modelFile = MODEL_PATH . $model . 'Model.php';

        if (file_exists($modelFile)) {
            // Include model file if not auto-loaded
            if (!class_exists($modelClass)) {
                require_once $modelFile;
            }

            // Check if model class exists
            if (class_exists($modelClass)) {
                $this->model = new $modelClass();
                return $this->model;
            } else {
                throw new \Exception("Model class {$modelClass} not found");
            }
        } else {
            throw new \Exception("Model file {$modelFile} not found");
        }
    }

    /**
     * Load view
     *
     * @param string $view View file
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function view($view, $data = [])
    {
        // Convert view name to path
        $viewFile = VIEW_PATH . str_replace('.', '/', $view) . '.php';

        // Check if view file exists
        if (file_exists($viewFile)) {
            // Extract data to make it available in the view
            extract($data);

            // Start output buffering
            ob_start();

            // Include the view file
            include $viewFile;

            // Get the content of the buffer
            $content = ob_get_clean();

            // Output the content
            if ($this->response) {
                $this->response->html($content);
            } else {
                echo $content;
            }
        } else {
            throw new \Exception("View file {$viewFile} not found");
        }
    }

    /**
     * Redirect to another URL
     *
     * @param string $url
     * @param int $status
     * @return void
     */
    protected function redirect($url, $status = 302)
    {
        if ($this->response) {
            $this->response->redirect($url, $status);
        } else {
            header("Location: $url", true, $status);
            exit;
        }
    }

    /**
     * Return JSON response
     *
     * @param mixed $data
     * @param int $status
     * @return void
     */
    protected function json($data, $status = 200)
    {
        if ($this->response) {
            $this->response->json($data, $status);
        } else {
            header('Content-Type: application/json');
            http_response_code($status);
            echo json_encode($data);
            exit;
        }
    }
}

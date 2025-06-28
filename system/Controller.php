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
     * Session instance
     *
     * @var Session
     */
    protected $session;

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
     * Set session object
     *
     * @param Session $session
     * @return void
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
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
     * Default layout for views
     * 
     * @var string|null
     */
    protected $layout = null;

    /**
     * Load view with template engine
     *
     * @param string $view View file
     * @param array $data Data to pass to the view
     * @param string|null $layout Layout to use (null for no layout or default)
     * @return mixed
     */
    protected function view($view, $data = [], $layout = null)
    {
        // Use specified layout or controller default
        $layout = $layout ?? $this->layout;

        // Render view with template engine
        $content = \System\View::render($view, $data, $layout);

        // Output the content
        if ($this->response) {
            return $this->response->html($content);
        }

        return $content;
    }

    /**
     * Set layout for all views in this controller
     * 
     * @param string|null $layout
     * @return void
     */
    protected function setLayout($layout)
    {
        $this->layout = $layout;
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

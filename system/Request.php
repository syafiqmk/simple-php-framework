<?php

namespace System;

/**
 * Request Class
 *
 * Handles HTTP request data
 */
class Request
{
    /**
     * Get request method
     *
     * @return string
     */
    public function method()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get request URI
     *
     * @return string
     */
    public function uri()
    {
        $uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/' ? $_SERVER['REQUEST_URI'] : '');

        // Remove base path from URI if needed
        $basePath = parse_url(APP_URL, PHP_URL_PATH);
        if (!empty($basePath) && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        return trim($uri, '/');
    }

    /**
     * Get all input data
     *
     * @return array
     */
    public function all()
    {
        return array_merge($_GET, $_POST);
    }

    /**
     * Get input value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function input($key, $default = null)
    {
        $data = $this->all();
        return isset($data[$key]) ? $data[$key] : $default;
    }

    /**
     * Check if input exists
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($_REQUEST[$key]);
    }

    /**
     * Get only specified inputs
     *
     * @param array $keys
     * @return array
     */
    public function only(array $keys)
    {
        $data = $this->all();
        $result = [];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $result[$key] = $data[$key];
            }
        }

        return $result;
    }

    /**
     * Get all inputs except specified
     *
     * @param array $keys
     * @return array
     */
    public function except(array $keys)
    {
        $data = $this->all();

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Check if request is AJAX
     *
     * @return bool
     */
    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Get uploaded file
     *
     * @param string $key
     * @return array|null
     */
    public function file($key)
    {
        return isset($_FILES[$key]) ? $_FILES[$key] : null;
    }
}

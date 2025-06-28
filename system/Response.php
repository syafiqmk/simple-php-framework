<?php

namespace System;

/**
 * Response Class
 *
 * Handles HTTP responses
 */
class Response
{
    /**
     * HTTP status code
     * 
     * @var int
     */
    protected $statusCode = 200;

    /**
     * Response content
     * 
     * @var string
     */
    protected $content = '';

    /**
     * Set response status code
     *
     * @param int $code
     * @return self
     */
    public function status($code)
    {
        $this->statusCode = $code;

        // Only actually set the HTTP status in non-test mode
        if (!defined('SESSION_TEST_MODE')) {
            http_response_code($code);
        }

        return $this;
    }

    /**
     * Get the current status code
     * 
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set response content
     * 
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get response content
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Headers array
     * 
     * @var array
     */
    protected $headers = [];

    /**
     * Set response header
     *
     * @param string $name
     * @param string $value
     * @return self
     */
    public function header($name, $value)
    {
        $this->headers[$name] = $value;

        // Only actually set the header in non-test mode
        if (!defined('SESSION_TEST_MODE')) {
            header("$name: $value");
        }

        return $this;
    }

    /**
     * Get headers
     * 
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * JSON data
     * 
     * @var mixed
     */
    protected $jsonData = null;

    /**
     * Send a JSON response
     *
     * @param mixed $data
     * @param int $status
     * @return self
     */
    public function json($data, $status = 200)
    {
        $this->jsonData = $data;
        $this->header('Content-Type', 'application/json')
            ->status($status)
            ->setContent(json_encode($data));

        if (!defined('SESSION_TEST_MODE')) {
            echo $this->content;
            exit;
        }

        return $this;
    }

    /**
     * Get JSON data
     * 
     * @return mixed
     */
    public function getJsonData()
    {
        return $this->jsonData;
    }

    /**
     * Redirect URL
     * 
     * @var string|null
     */
    protected $redirectUrl = null;

    /**
     * Redirect to another URL
     *
     * @param string $url
     * @param int $status
     * @return self
     */
    public function redirect($url, $status = 302)
    {
        $this->redirectUrl = $url;
        $this->status($status);

        if (!defined('SESSION_TEST_MODE')) {
            header("Location: $url");
            exit;
        }

        return $this;
    }

    /**
     * Get redirect URL
     * 
     * @return string|null
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Send plain text response
     *
     * @param string $text
     * @param int $status
     * @return void
     */
    public function text($text, $status = 200)
    {
        $this->header('Content-Type', 'text/plain')
            ->status($status);

        echo $text;
        exit;
    }

    /**
     * Send HTML response
     *
     * @param string $html
     * @param int $status
     * @return void
     */
    public function html($html, $status = 200)
    {
        $this->header('Content-Type', 'text/html')
            ->status($status);

        echo $html;
        exit;
    }
}

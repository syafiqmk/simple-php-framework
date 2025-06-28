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
     * Set response status code
     *
     * @param int $code
     * @return self
     */
    public function status($code)
    {
        http_response_code($code);
        return $this;
    }

    /**
     * Set response header
     *
     * @param string $name
     * @param string $value
     * @return self
     */
    public function header($name, $value)
    {
        header("$name: $value");
        return $this;
    }

    /**
     * Send a JSON response
     *
     * @param mixed $data
     * @param int $status
     * @return void
     */
    public function json($data, $status = 200)
    {
        $this->header('Content-Type', 'application/json')
            ->status($status);

        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to another URL
     *
     * @param string $url
     * @param int $status
     * @return void
     */
    public function redirect($url, $status = 302)
    {
        $this->status($status);
        header("Location: $url");
        exit;
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

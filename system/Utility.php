<?php

namespace System;

/**
 * Utility Class
 *
 * Contains utility functions for the application
 */
class Utility
{
    /**
     * Sanitize input
     *
     * @param string $input
     * @return string
     */
    public static function sanitize($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate random string
     *
     * @param int $length
     * @return string
     */
    public static function randomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * Format date
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($date));
    }

    /**
     * Get current URL
     *
     * @return string
     */
    public static function currentUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Get base URL
     *
     * @return string
     */
    public static function baseUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST'] . parse_url(APP_URL, PHP_URL_PATH);
    }

    /**
     * Redirect to URL
     *
     * @param string $url
     * @return void
     */
    public static function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Get file extension
     *
     * @param string $filename
     * @return string
     */
    public static function getFileExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * Check if AJAX request
     *
     * @return bool
     */
    public static function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Get client IP
     *
     * @return string
     */
    public static function getClientIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Debug variable
     *
     * @param mixed $var
     * @param bool $die
     * @return void
     */
    public static function debug($var, $die = true)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';

        if ($die) {
            die();
        }
    }
}

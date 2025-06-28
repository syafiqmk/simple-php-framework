<?php

namespace System;

/**
 * Session Class
 *
 * Handles session management
 */
class Session
{
    /**
     * Start session
     *
     * @return void
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set session value
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        self::start();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Check if session key exists
     *
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session key
     *
     * @param string $key
     * @return void
     */
    public static function remove($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Get session value and remove it
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function flash($key, $default = null)
    {
        self::start();
        $value = self::get($key, $default);
        self::remove($key);
        return $value;
    }

    /**
     * Set flash message
     *
     * @param string $type
     * @param string $message
     * @return void
     */
    public static function setFlash($type, $message)
    {
        self::set('flash_' . $type, $message);
    }

    /**
     * Get flash message
     *
     * @param string $type
     * @return string|null
     */
    public static function getFlash($type)
    {
        return self::flash('flash_' . $type);
    }

    /**
     * Destroy session
     *
     * @return void
     */
    public static function destroy()
    {
        self::start();
        session_destroy();
        $_SESSION = [];
    }
}

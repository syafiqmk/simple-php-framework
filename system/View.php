<?php

namespace System;

use System\View\Engine;

/**
 * View Class
 * 
 * Helper class for working with views
 */
class View
{
    /**
     * Data to pass to view
     * 
     * @var array
     */
    protected static $shared = [];

    /**
     * Default layout
     * 
     * @var string|null
     */
    protected static $layout = null;

    /**
     * Render a view
     * 
     * @param string $view
     * @param array $data
     * @param string|null $layout
     * @return string
     */
    public static function render($view, $data = [], $layout = null)
    {
        // Merge with shared data
        $data = array_merge(static::$shared, $data);

        // Use default layout if not specified
        if ($layout === null && static::$layout !== null) {
            $layout = static::$layout;
        }

        return Engine::make($view, $data, $layout);
    }

    /**
     * Set default layout
     * 
     * @param string $layout
     * @return void
     */
    public static function setLayout($layout)
    {
        static::$layout = $layout;
    }

    /**
     * Get default layout
     * 
     * @return string|null
     */
    public static function getLayout()
    {
        return static::$layout;
    }

    /**
     * Share data with all views
     * 
     * @param string|array $key
     * @param mixed $value
     * @return void
     */
    public static function share($key, $value = null)
    {
        if (is_array($key)) {
            static::$shared = array_merge(static::$shared, $key);
        } else {
            static::$shared[$key] = $value;
        }
    }

    /**
     * Get shared data
     * 
     * @param string|null $key
     * @return mixed
     */
    public static function getShared($key = null)
    {
        if ($key) {
            return static::$shared[$key] ?? null;
        }

        return static::$shared;
    }

    /**
     * Create a new view instance
     * 
     * @param string $view
     * @param array $data
     * @param string|null $layout
     * @return Engine
     */
    public static function make($view, $data = [], $layout = null)
    {
        // Merge with shared data
        $data = array_merge(static::$shared, $data);

        // Use default layout if not specified
        if ($layout === null && static::$layout !== null) {
            $layout = static::$layout;
        }

        return new Engine($view, $data, $layout);
    }

    /**
     * Magic method to pass other method calls to Engine
     * 
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        return call_user_func_array([Engine::class, $method], $args);
    }
}

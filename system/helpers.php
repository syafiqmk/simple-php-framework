<?php

/**
 * Framework Helper Functions
 */

if (!function_exists('route')) {
    /**
     * Generate URL for named route
     *
     * @param string $name
     * @param array $parameters
     * @return string|null
     */
    function route($name, $parameters = [])
    {
        return \System\Route::url($name, $parameters);
    }
}

if (!function_exists('redirect_to')) {
    /**
     * Redirect to named route
     *
     * @param string $name
     * @param array $parameters
     * @param int $status
     * @return void
     */
    function redirect_to($name, $parameters = [], $status = 302)
    {
        $url = \System\Route::url($name, $parameters);

        if ($url) {
            header("Location: {$url}", true, $status);
            exit;
        }

        throw new \Exception("Route with name {$name} not found");
    }
}

if (!function_exists('url')) {
    /**
     * Generate absolute URL
     *
     * @param string $path
     * @return string
     */
    function url($path = '')
    {
        $path = trim($path, '/');

        $baseUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $baseUrl .= $_SERVER['HTTP_HOST'];

        if (!empty($path)) {
            $baseUrl .= '/' . $path;
        }

        return $baseUrl;
    }
}

if (!function_exists('asset')) {
    /**
     * Generate URL for asset
     *
     * @param string $path
     * @return string
     */
    function asset($path)
    {
        $path = trim($path, '/');
        return url('public/' . $path);
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key, $default = null)
    {
        static $config = null;

        if ($config === null) {
            $configFile = BASE_PATH . '/config/config.php';

            if (file_exists($configFile)) {
                $config = require $configFile;
            } else {
                $config = [];
            }
        }

        $keys = explode('.', $key);
        $value = $config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }

            $value = $value[$k];
        }

        return $value;
    }
}

if (!function_exists('session')) {
    /**
     * Get or set session value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function session($key = null, $value = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($key === null) {
            return $_SESSION;
        }

        if ($value !== null) {
            $_SESSION[$key] = $value;
            return $value;
        }

        return $_SESSION[$key] ?? null;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Generate or get CSRF token
     *
     * @return string
     */
    function csrf_token()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate CSRF field
     *
     * @return string
     */
    function csrf_field()
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('view')) {
    /**
     * Render view with data
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    function view($view, $data = [])
    {
        $viewFile = VIEW_PATH . str_replace('.', '/', $view) . '.php';

        if (file_exists($viewFile)) {
            extract($data);

            ob_start();
            include $viewFile;
            echo ob_get_clean();
        } else {
            throw new \Exception("View file {$viewFile} not found");
        }
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die - untuk debugging
     *
     * @param mixed ...$vars
     * @return void
     */
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }

        exit(1);
    }
}

if (!function_exists('json')) {
    /**
     * Return JSON response
     *
     * @param array $data
     * @param int $statusCode
     * @return void
     */
    function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}

if (!function_exists('route_list')) {
    /**
     * Get list of all routes (for debugging)
     *
     * @return array
     */
    function route_list()
    {
        return [
            'routes' => \System\Route::getRoutes(),
            'named_routes' => \System\Route::getNamedRoutes()
        ];
    }
}

if (!function_exists('back')) {
    /**
     * Redirect to previous page
     *
     * @param int $statusCode
     * @return void
     */
    function back($statusCode = 302)
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: {$referer}", true, $statusCode);
        exit;
    }
}

<?php

namespace System\View;

/**
 * Template Engine Class
 * 
 * Provides template rendering features with layouts, sections, and includes
 */
class Engine
{
    /**
     * View template data
     * 
     * @var array
     */
    protected $data = [];

    /**
     * View file path
     * 
     * @var string
     */
    protected $view;

    /**
     * Layout file path
     * 
     * @var string|null
     */
    protected $layout = null;

    /**
     * Sections content
     * 
     * @var array
     */
    protected static $sections = [];

    /**
     * Current section being captured
     * 
     * @var string|null
     */
    protected static $currentSection = null;

    /**
     * Section content buffer
     * 
     * @var array
     */
    protected static $sectionBuffer = [];

    /**
     * Constructor
     * 
     * @param string $view
     * @param array $data
     * @param string|null $layout
     */
    public function __construct($view = null, $data = [], $layout = null)
    {
        $this->view = $view;
        $this->data = $data;
        $this->layout = $layout;
    }

    /**
     * Set view file
     * 
     * @param string $view
     * @return self
     */
    public function view($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Set layout file
     * 
     * @param string $layout
     * @return self
     */
    public function layout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Set view data
     * 
     * @param array $data
     * @return self
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Set view file path
     * 
     * @param string $view
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Set view data
     * 
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set layout file path
     * 
     * @param string $layout
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Render view
     * 
     * @return string
     */
    public function render()
    {
        // Clear previous sections
        static::$sections = [];

        // Check if view exists
        $viewFile = $this->resolvePath($this->view);

        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$this->view}");
        }

        // Render view content
        $content = $this->renderFile($viewFile, $this->data);

        // If has layout, render layout with content
        if ($this->layout) {
            // Store content in 'content' section if not already set
            if (!isset(static::$sections['content'])) {
                static::$sections['content'] = $content;
            }

            $layoutFile = $this->resolvePath($this->layout, 'layouts');

            if (!file_exists($layoutFile)) {
                throw new \Exception("Layout file not found: {$this->layout}");
            }

            return $this->renderFile($layoutFile, $this->data);
        }

        return $content;
    }

    /**
     * Resolve file path from view name
     * 
     * @param string $name
     * @param string $subdirectory
     * @return string
     */
    protected function resolvePath($name, $subdirectory = '')
    {
        // If $name is already a full path (used in tests)
        if (file_exists($name)) {
            return $name;
        }

        $name = str_replace('.', '/', $name);

        // Check if VIEW_PATH constant is defined (in test environment it might not be)
        if (defined('VIEW_PATH')) {
            $basePath = VIEW_PATH;
        } else {
            // For test environment, use the test views directory
            $basePath = dirname(__DIR__, 2) . '/tests/views/';
        }

        if (!empty($subdirectory)) {
            $path = $basePath . $subdirectory . '/' . $name . '.php';
        } else {
            $path = $basePath . $name . '.php';
        }

        return $path;
    }

    /**
     * Render file with data
     * 
     * @param string $file
     * @param array $data
     * @return string
     */
    protected function renderFile($file, $data = [])
    {
        // Extract data to variables
        extract($data);

        // Start output buffer
        ob_start();

        // Include file
        include $file;

        // Get content and clean buffer
        return ob_get_clean();
    }

    /**
     * Render a view
     * 
     * @param string $view
     * @param array $data
     * @param string|null $layout
     * @return string
     */
    public static function make($view, $data = [], $layout = null)
    {
        $engine = new static($view, $data, $layout);
        return $engine->render();
    }

    /**
     * Start section capture
     * 
     * @param string $name
     * @return void
     */
    public static function section($name)
    {
        static::$currentSection = $name;
        ob_start();
    }

    /**
     * End section capture
     * 
     * @return void
     */
    public static function endSection()
    {
        if (static::$currentSection) {
            static::$sections[static::$currentSection] = ob_get_clean();
            static::$currentSection = null;
        }
    }

    /**
     * Extend a layout
     * 
     * @param string $layout
     * @return void
     */
    public static function extend($layout)
    {
        $engine = new static();
        $engine->layout = $layout;
    }

    /**
     * Show content of a section
     * 
     * @param string $name
     * @param string $default
     * @return string
     */
    public static function yield($name, $default = '')
    {
        return static::$sections[$name] ?? $default;
    }

    /**
     * Include another view
     * 
     * @param string $view
     * @param array $data
     * @return string
     */
    public static function include($view, $data = [])
    {
        $engine = new static();
        $viewFile = $engine->resolvePath($view);

        if (!file_exists($viewFile)) {
            throw new \Exception("Include view not found: {$view}");
        }

        return $engine->renderFile($viewFile, $data);
    }

    /**
     * Check if section exists
     * 
     * @param string $name
     * @return boolean
     */
    public static function hasSection($name)
    {
        return isset(static::$sections[$name]);
    }

    /**
     * Output escaped content
     * 
     * @param string $content
     * @return string
     */
    public static function e($content)
    {
        return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
    }
}

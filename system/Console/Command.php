<?php

namespace System\Console;

/**
 * Base Command Class
 * 
 * All commands must extend this class
 */
abstract class Command
{
    /**
     * Command name
     * 
     * @var string
     */
    protected $name = '';

    /**
     * Command description
     * 
     * @var string
     */
    protected $description = '';

    /**
     * Command signature
     * 
     * Format: command:name {argument} {--option}
     * 
     * @var string
     */
    protected $signature = '';

    /**
     * Arguments received from command line
     * 
     * @var array
     */
    protected $arguments = [];

    /**
     * Options received from command line
     * 
     * @var array
     */
    protected $options = [];

    /**
     * Get command name
     * 
     * @return string
     */
    public function getName()
    {
        if (!empty($this->name)) {
            return $this->name;
        }

        // Parse name from signature
        if (!empty($this->signature)) {
            $parts = explode(' ', $this->signature);
            return $parts[0];
        }

        return '';
    }

    /**
     * Get command description
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get command signature
     * 
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Parse arguments from input array
     * 
     * @param array $argv
     * @return void
     */
    public function parseArguments($argv)
    {
        // Skip first two elements (script name and command name)
        $args = array_slice($argv, 2);

        $this->arguments = [];
        $this->options = [];

        // Parse options and arguments
        foreach ($args as $arg) {
            // Check if it's an option (--option or -o)
            if (substr($arg, 0, 2) === '--') {
                $option = substr($arg, 2);

                // Check for --option=value format
                if (strpos($option, '=') !== false) {
                    list($key, $value) = explode('=', $option, 2);
                    $this->options[$key] = $value;
                } else {
                    $this->options[$option] = true;
                }
            } elseif (substr($arg, 0, 1) === '-') {
                $option = substr($arg, 1);
                $this->options[$option] = true;
            } else {
                // It's a regular argument
                $this->arguments[] = $arg;
            }
        }
    }

    /**
     * Get argument value by index
     * 
     * @param int $index
     * @param mixed $default
     * @return mixed
     */
    protected function argument($index, $default = null)
    {
        return isset($this->arguments[$index]) ? $this->arguments[$index] : $default;
    }

    /**
     * Get option value by name
     * 
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    protected function option($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * Check if option exists
     * 
     * @param string $name
     * @return bool
     */
    protected function hasOption($name)
    {
        return isset($this->options[$name]);
    }

    /**
     * Write line to console
     * 
     * @param string $text
     * @return void
     */
    protected function line($text)
    {
        echo $text . "\n";
    }

    /**
     * Write info message
     * 
     * @param string $text
     * @return void
     */
    protected function info($text)
    {
        echo "\033[32m" . $text . "\033[0m\n";
    }

    /**
     * Write error message
     * 
     * @param string $text
     * @return void
     */
    protected function error($text)
    {
        echo "\033[31m" . $text . "\033[0m\n";
    }

    /**
     * Write warning message
     * 
     * @param string $text
     * @return void
     */
    protected function warning($text)
    {
        echo "\033[33m" . $text . "\033[0m\n";
    }

    /**
     * Ask for input
     * 
     * @param string $question
     * @param mixed $default
     * @return string
     */
    protected function ask($question, $default = null)
    {
        $defaultText = $default !== null ? " [" . $default . "]" : '';
        echo $question . $defaultText . ": ";

        $handle = fopen("php://stdin", "r");
        $input = trim(fgets($handle));
        fclose($handle);

        if (empty($input) && $default !== null) {
            return $default;
        }

        return $input;
    }

    /**
     * Ask for confirmation
     * 
     * @param string $question
     * @param bool $default
     * @return bool
     */
    protected function confirm($question, $default = false)
    {
        $defaultText = $default ? 'Y/n' : 'y/N';
        $input = $this->ask($question . " (" . $defaultText . ")");

        if (empty($input)) {
            return $default;
        }

        return strtolower($input) === 'y' || strtolower($input) === 'yes';
    }

    /**
     * Execute the command
     * 
     * @return mixed
     */
    abstract public function handle();
}

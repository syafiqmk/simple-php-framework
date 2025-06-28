<?php

namespace System\Console;

/**
 * Command Runner Class
 * 
 * Finds and executes commands based on user input
 */
class CommandRunner
{
    /**
     * Command arguments
     * 
     * @var array
     */
    protected $argv;

    /**
     * Available commands
     * 
     * @var array
     */
    protected $commands = [];

    /**
     * Constructor
     * 
     * @param array $argv
     */
    public function __construct($argv)
    {
        $this->argv = $argv;
        $this->registerCommands();
    }

    /**
     * Register available commands
     * 
     * @return void
     */
    protected function registerCommands()
    {
        // Core commands
        $this->registerCommand(new Commands\HelpCommand());
        $this->registerCommand(new Commands\ServeCommand());
        $this->registerCommand(new Commands\MigrateCommand());
        $this->registerCommand(new Commands\MakeModelCommand());
        $this->registerCommand(new Commands\MakeControllerCommand());
        $this->registerCommand(new Commands\MakeCommandCommand());

        // Register custom commands from provider if exists
        $providerClass = 'App\\Console\\CommandServiceProvider';
        if (class_exists($providerClass)) {
            $providerClass::register($this);
        }
    }

    /**
     * Register a command
     * 
     * @param Command $command
     * @return void
     */
    public function registerCommand(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * Run the command
     * 
     * @return mixed
     */
    public function run()
    {
        // Get the command name from arguments (or default to help)
        $commandName = isset($this->argv[1]) ? $this->argv[1] : 'help';

        // Check if command exists
        if (!isset($this->commands[$commandName])) {
            $this->showCommandNotFound($commandName);
            return;
        }

        // Get the command instance
        $command = $this->commands[$commandName];

        // Parse command arguments
        $command->parseArguments($this->argv);

        // Execute the command
        return $command->handle();
    }

    /**
     * Show command not found message with suggestions
     * 
     * @param string $commandName
     * @return void
     */
    protected function showCommandNotFound($commandName)
    {
        echo "\n\033[31mCommand \"{$commandName}\" not found.\033[0m\n\n";

        // Find similar commands
        $similar = [];
        foreach ($this->commands as $name => $command) {
            $distance = levenshtein($commandName, $name);
            if ($distance <= 3) {
                $similar[$name] = $distance;
            }
        }

        // Show suggestions if any
        if (!empty($similar)) {
            asort($similar);
            echo "Did you mean one of these?\n";
            foreach (array_keys($similar) as $suggestion) {
                echo " - {$suggestion}\n";
            }
        }

        echo "\nRun 'flash help' to see all available commands.\n\n";
    }

    /**
     * Get all available commands
     * 
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }
}

<?php

namespace App\Console;

/**
 * Command Service Provider
 * 
 * Registers custom commands with the command runner
 */
class CommandServiceProvider
{
    /**
     * Register commands with the command runner
     * 
     * @param \System\Console\CommandRunner $runner
     * @return void
     */
    public static function register($runner)
    {
        // Register all custom commands here
        $runner->registerCommand(new Commands\SendEmailCommand());

        // You can add more custom commands here
    }
}

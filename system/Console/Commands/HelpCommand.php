<?php

namespace System\Console\Commands;

use System\Console\Command;

/**
 * Help Command
 * 
 * Lists all available commands
 */
class HelpCommand extends Command
{
    /**
     * Command signature
     * 
     * @var string
     */
    protected $signature = 'help';

    /**
     * Command description
     * 
     * @var string
     */
    protected $description = 'Display help for available commands';

    /**
     * Execute the command
     * 
     * @return mixed
     */
    public function handle()
    {
        // Get command runner instance
        $runner = new \System\Console\CommandRunner($GLOBALS['argv']);
        $commands = $runner->getCommands();

        // Display header
        $this->displayLogo();
        $this->line("\n<fg=blue;options=bold>SIMPLE PHP FRAMEWORK CLI TOOL</fg=>\n");
        $this->line("Usage: flash <command> [options] [arguments]\n");

        // Group commands by category
        $categories = [
            'Main Commands' => [],
            'Make Commands' => [],
            'Database Commands' => [],
            'Other Commands' => []
        ];

        foreach ($commands as $name => $command) {
            $commandName = $command->getName();

            if (strpos($commandName, 'make:') === 0) {
                $categories['Make Commands'][$commandName] = $command;
            } elseif (in_array($commandName, ['help', 'serve', 'list'])) {
                $categories['Main Commands'][$commandName] = $command;
            } elseif (strpos($commandName, 'migrate') === 0 || strpos($commandName, 'db:') === 0) {
                $categories['Database Commands'][$commandName] = $command;
            } else {
                $categories['Other Commands'][$commandName] = $command;
            }
        }

        // Display commands by category
        foreach ($categories as $category => $categoryCommands) {
            if (empty($categoryCommands)) continue;

            $this->line("\n<fg=yellow;options=bold>" . $category . "</fg=>");

            // Sort commands alphabetically
            ksort($categoryCommands);

            foreach ($categoryCommands as $name => $command) {
                $this->line(sprintf("  <fg=green>%-20s</fg=> %s", $name, $command->getDescription()));
            }
        }

        $this->line("\nFor more info about a command, use: flash help <command>");
        return 0;
    }

    /**
     * Display the framework logo
     * 
     * @return void
     */
    protected function displayLogo()
    {
        $logo = <<<EOT
   ______           __  
  / __/ /__ ___ ___/ /  
 _\ \/ / _ \/ // / _ \  
/___/_/\___/\_,_/_//_/
EOT;

        $this->line("\n<fg=magenta>" . $logo . "</fg=>");
    }
}

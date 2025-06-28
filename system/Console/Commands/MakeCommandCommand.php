<?php

namespace System\Console\Commands;

use System\Console\Command;

/**
 * Make Command Command
 * 
 * Creates a new custom command
 */
class MakeCommandCommand extends Command
{
    /**
     * Command signature
     * 
     * @var string
     */
    protected $signature = 'make:command';

    /**
     * Command description
     * 
     * @var string
     */
    protected $description = 'Create a new custom command';

    /**
     * Execute the command
     * 
     * @return mixed
     */
    public function handle()
    {
        // Get command name from argument or ask for it
        $name = $this->argument(0);
        if (empty($name)) {
            $name = $this->ask('Command name (e.g. SendEmails)');

            if (empty($name)) {
                $this->error('Command name cannot be empty');
                return 1;
            }
        }

        // Convert to proper case and ensure "Command" suffix
        $name = ucfirst($name);
        if (!str_ends_with($name, 'Command')) {
            $name .= 'Command';
        }

        // Get command signature/name
        $signature = $this->option('signature');
        if (empty($signature)) {
            $suggestion = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', str_replace('Command', '', $name)));
            $signature = $this->ask('Command signature (how it will be called from CLI)', $suggestion);
        }

        // Create app/Console/Commands directory if not exists
        $directory = BASE_PATH . '/app/Console/Commands';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Check if command already exists
        $filePath = $directory . '/' . $name . '.php';
        if (file_exists($filePath)) {
            if (!$this->confirm("Command '{$name}' already exists. Overwrite?", false)) {
                $this->line('Operation cancelled.');
                return 0;
            }
        }

        // Generate command content
        $content = $this->generateCommand($name, $signature);

        // Save file
        file_put_contents($filePath, $content);

        $this->info("Command created: {$name}");
        $this->line($filePath);

        // Inform about registering the command
        $this->line("\nTo use this command, you need to register it in:");
        $this->line("1. Create app/Console/CommandServiceProvider.php if it doesn't exist");
        $this->line("2. Register your command there");
        $this->line("3. Make sure to autoload in composer.json");

        return 0;
    }

    /**
     * Generate command class content
     * 
     * @param string $name
     * @param string $signature
     * @return string
     */
    protected function generateCommand($name, $signature)
    {
        $content = <<<EOT
<?php

namespace App\Console\Commands;

use System\Console\Command;

/**
 * {$name} Class
 */
class {$name} extends Command
{
    /**
     * Command signature
     * 
     * @var string
     */
    protected \$signature = '{$signature}';
    
    /**
     * Command description
     * 
     * @var string
     */
    protected \$description = 'Description of the command';
    
    /**
     * Execute the command
     * 
     * @return mixed
     */
    public function handle()
    {
        \$this->info('Command "{$signature}" is running!');
        
        // Your command logic goes here
        
        \$this->line('Command completed successfully.');
        return 0;
    }
}

EOT;

        return $content;
    }
}

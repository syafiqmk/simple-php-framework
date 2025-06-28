<?php

namespace System\Console\Commands;

use System\Console\Command;

/**
 * Serve Command
 * 
 * Starts the PHP development server
 */
class ServeCommand extends Command
{
    /**
     * Command signature
     * 
     * @var string
     */
    protected $signature = 'serve';

    /**
     * Command description
     * 
     * @var string
     */
    protected $description = 'Start the PHP development server';

    /**
     * Execute the command
     * 
     * @return mixed
     */
    public function handle()
    {
        // Get port and host from options or use defaults
        $port = $this->option('port', 8000);
        $host = $this->option('host', 'localhost');

        // Start development server
        $command = sprintf(
            'php -S %s:%d -t %s/public',
            $host,
            $port,
            BASE_PATH
        );

        $this->info("Starting development server on http://{$host}:{$port}");
        $this->line("Document root: " . BASE_PATH . "/public");
        $this->line("Press Ctrl+C to stop the server\n");

        // Execute the command
        passthru($command);

        return 0;
    }
}

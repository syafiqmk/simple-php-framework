<?php

namespace System\Console\Commands;

use System\Console\Command;

/**
 * Migrate Command
 * 
 * Runs database migrations
 */
class MigrateCommand extends Command
{
    /**
     * Command signature
     * 
     * @var string
     */
    protected $signature = 'migrate';

    /**
     * Command description
     * 
     * @var string
     */
    protected $description = 'Run database migrations';

    /**
     * Execute the command
     * 
     * @return mixed
     */
    public function handle()
    {
        // Check if need to show help
        if ($this->hasOption('help')) {
            return $this->showHelp();
        }

        // Load migration class
        if (!class_exists('\\System\\Database\\Migration')) {
            require_once BASE_PATH . '/system/Database/Migration.php';
        }

        // Create migration instance
        $migration = new \System\Database\Migration();

        // Check for specific operation
        if ($this->hasOption('create')) {
            $name = $this->option('create');
            if (empty($name)) {
                $name = $this->ask('Enter the name for the migration');
                if (empty($name)) {
                    $this->error('Migration name cannot be empty');
                    return 1;
                }
            }

            $migration->create($name);
            $this->info("Migration created: {$name}");
            return 0;
        }

        // Check for reset option
        if ($this->hasOption('reset')) {
            if (!$this->confirm('This will reset all database migrations. Continue?', false)) {
                $this->line('Operation cancelled.');
                return 0;
            }

            $this->info("Resetting all migrations...");
            $migration->reset();
            $this->info("All migrations have been reset.");
            return 0;
        }

        // Check for rollback option
        if ($this->hasOption('rollback')) {
            $steps = $this->option('step', 1);

            $this->info("Rolling back {$steps} migration(s)...");
            $migration->rollback($steps);
            $this->info("Rollback completed.");
            return 0;
        }

        // Default: run migrations
        $this->info("Running migrations...");
        $migration->run();
        $this->info("Migrations completed successfully.");
        return 0;
    }

    /**
     * Show command help
     * 
     * @return int
     */
    protected function showHelp()
    {
        $this->line('Usage:');
        $this->line('  flash migrate                     Run all pending migrations');
        $this->line('  flash migrate --create=name      Create a new migration');
        $this->line('  flash migrate --reset             Reset all migrations');
        $this->line('  flash migrate --rollback          Rollback the last batch of migrations');
        $this->line('  flash migrate --rollback --step=n Rollback n migrations');
        $this->line('  flash migrate --help              Show this help message');
        return 0;
    }
}

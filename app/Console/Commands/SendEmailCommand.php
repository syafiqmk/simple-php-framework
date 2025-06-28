<?php

namespace App\Console\Commands;

use System\Console\Command;

/**
 * SendEmailCommand Class
 */
class SendEmailCommand extends Command
{
    /**
     * Command signature
     * 
     * @var string
     */
    protected $signature = 'send-email';
    
    /**
     * Command description
     * 
     * @var string
     */
    protected $description = 'Description of the command';
    
    /**
     * Execute the command
     * 
     * @return mixed
     */
    public function handle()
    {
        $this->info('Command "send-email" is running!');
        
        // Your command logic goes here
        
        $this->line('Command completed successfully.');
        return 0;
    }
}

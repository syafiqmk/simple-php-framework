<?php

namespace System\Console\Commands;

use System\Console\Command;

/**
 * Make Model Command
 * 
 * Creates a new model
 */
class MakeModelCommand extends Command
{
    /**
     * Command signature
     * 
     * @var string
     */
    protected $signature = 'make:model';

    /**
     * Command description
     * 
     * @var string
     */
    protected $description = 'Create a new model class';

    /**
     * Execute the command
     * 
     * @return mixed
     */
    public function handle()
    {
        // Get model name from argument or ask for it
        $name = $this->argument(0);
        if (empty($name)) {
            $name = $this->ask('Model name');

            if (empty($name)) {
                $this->error('Model name cannot be empty');
                return 1;
            }
        }

        // Convert to proper case and ensure "Model" suffix
        $name = ucfirst($name);
        if (!str_ends_with($name, 'Model')) {
            $name .= 'Model';
        }

        // Create directory if not exists
        $directory = BASE_PATH . '/app/models';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Check if model already exists
        $filePath = $directory . '/' . $name . '.php';
        if (file_exists($filePath)) {
            if (!$this->confirm("Model '{$name}' already exists. Overwrite?", false)) {
                $this->line('Operation cancelled.');
                return 0;
            }
        }

        // Generate model content
        $content = $this->generateModel($name);

        // Save file
        file_put_contents($filePath, $content);

        $this->info("Model created: {$name}");
        $this->line($filePath);

        return 0;
    }

    /**
     * Generate model class content
     * 
     * @param string $name
     * @return string
     */
    protected function generateModel($name)
    {
        // Get table name from model name (pluralize and snake case)
        $table = $this->pluralize(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', str_replace('Model', '', $name))));

        $content = <<<EOT
<?php

namespace App\Models;

use System\Model;

/**
 * {$name} Class
 */
class {$name} extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected \$table = '{$table}';
    
    /**
     * Primary key
     *
     * @var string
     */
    protected \$primaryKey = 'id';
    
    /**
     * Fillable fields
     *
     * @var array
     */
    protected \$fillable = [
        // Define your fillable fields here
    ];
}

EOT;

        return $content;
    }

    /**
     * Simple pluralize function for English words
     * 
     * @param string $word
     * @return string
     */
    protected function pluralize($word)
    {
        $irregulars = [
            'child' => 'children',
            'foot' => 'feet',
            'man' => 'men',
            'person' => 'people',
            'tooth' => 'teeth',
            'woman' => 'women',
        ];

        // Check for irregular plurals
        if (isset($irregulars[$word])) {
            return $irregulars[$word];
        }

        // Handle words ending with 'y'
        $lastChar = substr($word, -1);
        $secondLastChar = substr($word, -2, 1);

        if ($lastChar === 'y' && !in_array($secondLastChar, ['a', 'e', 'i', 'o', 'u'])) {
            return substr($word, 0, -1) . 'ies';
        }

        // Words ending with specific suffixes
        $suffix = substr($word, -2);
        if (in_array($suffix, ['ch', 'sh', 'ss', 'zz'])) {
            return $word . 'es';
        }

        // Default: add 's'
        return $word . 's';
    }
}

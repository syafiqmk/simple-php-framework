<?php

namespace System\Database;

/**
 * Migration Class
 *
 * Handles database migrations
 */
class Migration
{
    /**
     * PDO instance
     *
     * @var \PDO
     */
    protected $db;

    /**
     * Migration table
     *
     * @var string
     */
    protected $table = 'migrations';

    /**
     * Migration directory
     *
     * @var string
     */
    protected $migrationDir;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Connect to database
        $this->connectDatabase();

        // Set migrations directory
        $this->migrationDir = BASE_PATH . '/database/migrations/';

        // Create migrations table if it doesn't exist
        $this->createMigrationsTable();
    }

    /**
     * Connect to database
     *
     * @return void
     */
    private function connectDatabase()
    {
        try {
            // Create PDO connection
            $this->db = new \PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
                DB_USER,
                DB_PASS,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (\PDOException $e) {
            die('Database Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * Create migrations table
     *
     * @return void
     */
    private function createMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $this->db->exec($sql);
    }

    /**
     * Run migrations
     *
     * @return void
     */
    public function run()
    {
        // Get migration files
        $files = $this->getMigrationFiles();

        // Get applied migrations
        $applied = $this->getAppliedMigrations();

        // Find migrations that haven't been applied
        $pending = array_diff($files, $applied);

        if (empty($pending)) {
            echo "No pending migrations.\n";
            return;
        }

        // Sort migrations
        sort($pending);

        foreach ($pending as $migration) {
            $this->runMigration($migration);
        }

        echo "Migrations completed successfully.\n";
    }

    /**
     * Reset migrations
     *
     * @return void
     */
    public function reset()
    {
        // Get applied migrations in reverse order
        $applied = $this->getAppliedMigrations();
        rsort($applied);

        if (empty($applied)) {
            echo "No migrations to reset.\n";
            return;
        }

        foreach ($applied as $migration) {
            $this->rollbackMigration($migration);
        }

        echo "Migrations reset completed successfully.\n";
    }

    /**
     * Rollback last migration
     *
     * @param int $steps
     * @return void
     */
    public function rollback($steps = 1)
    {
        // Get applied migrations in reverse order
        $applied = $this->getAppliedMigrations();
        rsort($applied);

        if (empty($applied)) {
            echo "No migrations to rollback.\n";
            return;
        }

        // Limit to requested steps
        $toRollback = array_slice($applied, 0, $steps);

        foreach ($toRollback as $migration) {
            $this->rollbackMigration($migration);
        }

        echo "Rollback completed successfully.\n";
    }

    /**
     * Get migration files
     *
     * @return array
     */
    private function getMigrationFiles()
    {
        // Create migrations directory if it doesn't exist
        if (!is_dir($this->migrationDir)) {
            mkdir($this->migrationDir, 0755, true);
        }

        $files = scandir($this->migrationDir);
        $migrations = [];

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $migrations[] = pathinfo($file, PATHINFO_FILENAME);
            }
        }

        return $migrations;
    }

    /**
     * Get applied migrations
     *
     * @return array
     */
    private function getAppliedMigrations()
    {
        $stmt = $this->db->prepare("SELECT migration FROM {$this->table}");
        $stmt->execute();
        $applied = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        return $applied;
    }

    /**
     * Run migration
     *
     * @param string $migration
     * @return void
     */
    private function runMigration($migration)
    {
        // Include migration file
        $file = $this->migrationDir . $migration . '.php';

        if (!file_exists($file)) {
            echo "Migration file {$file} not found.\n";
            return;
        }

        require_once $file;

        // Create migration class name
        $className = 'App\\Database\\Migrations\\' . $migration;

        if (!class_exists($className)) {
            echo "Migration class {$className} not found.\n";
            return;
        }

        // Create instance and run migration
        $instance = new $className();

        echo "Running migration: {$migration}\n";

        // Begin transaction
        $this->db->beginTransaction();

        try {
            if (method_exists($instance, 'up')) {
                $instance->up($this->db);
            }

            // Record migration
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (migration) VALUES (:migration)");
            $stmt->bindParam(':migration', $migration);
            $stmt->execute();

            // Commit transaction
            $this->db->commit();

            echo "Migration completed: {$migration}\n";
        } catch (\Exception $e) {
            // Rollback transaction
            $this->db->rollBack();

            echo "Error running migration {$migration}: {$e->getMessage()}\n";
        }
    }

    /**
     * Rollback migration
     *
     * @param string $migration
     * @return void
     */
    private function rollbackMigration($migration)
    {
        // Include migration file
        $file = $this->migrationDir . $migration . '.php';

        if (!file_exists($file)) {
            echo "Migration file {$file} not found.\n";
            return;
        }

        require_once $file;

        // Create migration class name
        $className = 'App\\Database\\Migrations\\' . $migration;

        if (!class_exists($className)) {
            echo "Migration class {$className} not found.\n";
            return;
        }

        // Create instance and run migration
        $instance = new $className();

        echo "Rolling back migration: {$migration}\n";

        // Begin transaction
        $this->db->beginTransaction();

        try {
            if (method_exists($instance, 'down')) {
                $instance->down($this->db);
            }

            // Remove migration record
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE migration = :migration");
            $stmt->bindParam(':migration', $migration);
            $stmt->execute();

            // Commit transaction
            $this->db->commit();

            echo "Rollback completed: {$migration}\n";
        } catch (\Exception $e) {
            // Rollback transaction
            $this->db->rollBack();

            echo "Error rolling back migration {$migration}: {$e->getMessage()}\n";
        }
    }

    /**
     * Create a new migration
     *
     * @param string $name
     * @return void
     */
    public function create($name)
    {
        // Create migrations directory if it doesn't exist
        if (!is_dir($this->migrationDir)) {
            mkdir($this->migrationDir, 0755, true);
        }

        // Create migration file
        $filename = date('YmdHis') . '_' . $name . '.php';
        $file = $this->migrationDir . $filename;

        $content = "<?php\nnamespace App\\Database\\Migrations;\n\nclass " . date('YmdHis') . "_{$name} {\n\n    /**\n     * Run the migration\n     *\n     * @param \\PDO \$db\n     * @return void\n     */\n    public function up(\$db)\n    {\n        \$sql = \"\";\n        \$db->exec(\$sql);\n    }\n\n    /**\n     * Rollback the migration\n     *\n     * @param \\PDO \$db\n     * @return void\n     */\n    public function down(\$db)\n    {\n        \$sql = \"\";\n        \$db->exec(\$sql);\n    }\n}\n";

        file_put_contents($file, $content);

        echo "Migration created: {$filename}\n";
    }
}

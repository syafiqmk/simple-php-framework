<?php

namespace System;

/**
 * Base Model Class
 *
 * All models should extend this class
 */
class Model
{
    /**
     * Database connection
     *
     * @var \PDO
     */
    protected $db;

    /**
     * Table name
     *
     * @var string
     */
    protected $table;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->connectDatabase();
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
            if (APP_DEBUG) {
                echo 'Database Connection Error: ' . $e->getMessage();
            } else {
                echo 'Database Connection Error';
            }
            exit;
        }
    }

    /**
     * Find all records
     *
     * @return array
     */
    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Find record by ID
     *
     * @param int $id
     * @return array|bool
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create new record
     *
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        // Build query
        $fields = array_keys($data);
        $fieldsList = implode(', ', $fields);
        $placeholders = ':' . implode(', :', $fields);

        // Prepare and execute query
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$fieldsList}) VALUES ({$placeholders})");

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        return $stmt->execute();
    }

    /**
     * Update record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        // Build SET clause
        $setClause = '';
        foreach (array_keys($data) as $field) {
            $setClause .= "{$field} = :{$field}, ";
        }
        $setClause = rtrim($setClause, ', ');

        // Prepare and execute query
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$setClause} WHERE id = :id");

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        return $stmt->execute();
    }

    /**
     * Delete record
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Custom query
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public function query($query, $params = [])
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

<?php

namespace App\Models;

use System\Model;

/**
 * User Model
 *
 * Example model for user data
 */
class UserModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Get user by email
     *
     * @param string $email
     * @return array|bool
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Get user by username
     *
     * @param string $username
     * @return array|bool
     */
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create new user
     *
     * @param array $data
     * @return bool
     */
    public function createUser($data)
    {
        // Make sure password is hashed
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->create($data);
    }

    /**
     * Verify user password
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Update user
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser($id, $data)
    {
        // Make sure password is hashed if provided
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->update($id, $data);
    }
}

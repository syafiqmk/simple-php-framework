<?php

namespace App\Controllers\Api;

use System\Controller;

/**
 * Auth Controller for API
 */
class AuthController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadModel('User');
    }

    /**
     * Login method
     *
     * @return void
     */
    public function login()
    {
        // Get input data
        $username = $this->request->input('username');
        $password = $this->request->input('password');

        // Validate input
        if (empty($username) || empty($password)) {
            return $this->json([
                'status' => 'error',
                'message' => 'Username and password are required'
            ], 400);
        }

        // Check user credentials
        $user = $this->model->findByUsername($username);

        if ($user && $this->model->verifyPassword($password, $user['password'])) {
            // Generate API token
            $token = bin2hex(random_bytes(32));

            // Update user with token
            $this->model->update($user['id'], [
                'api_token' => $token,
                'token_expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))
            ]);

            // Return success with token
            return $this->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email']
                    ]
                ]
            ]);
        }

        // Return error for invalid credentials
        return $this->json([
            'status' => 'error',
            'message' => 'Invalid username or password'
        ], 401);
    }

    /**
     * Register method
     *
     * @return void
     */
    public function register()
    {
        // Get input data
        $username = $this->request->input('username');
        $email = $this->request->input('email');
        $password = $this->request->input('password');

        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            return $this->json([
                'status' => 'error',
                'message' => 'All fields are required'
            ], 400);
        }

        // Check if username or email exists
        if ($this->model->findByUsername($username)) {
            return $this->json([
                'status' => 'error',
                'message' => 'Username already exists'
            ], 400);
        }

        if ($this->model->findByEmail($email)) {
            return $this->json([
                'status' => 'error',
                'message' => 'Email already exists'
            ], 400);
        }

        // Create user
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->model->createUser($userData)) {
            return $this->json([
                'status' => 'success',
                'message' => 'User registered successfully'
            ], 201);
        }

        // Return error if user creation fails
        return $this->json([
            'status' => 'error',
            'message' => 'Failed to register user'
        ], 500);
    }

    /**
     * Logout method
     *
     * @return void
     */
    public function logout()
    {
        // Get token from header
        $token = $this->getTokenFromHeader();

        if ($token) {
            // Find user with token
            $user = $this->model->query("SELECT id FROM users WHERE api_token = ?", [$token]);

            if (!empty($user[0])) {
                // Invalidate token
                $this->model->update($user[0]['id'], [
                    'api_token' => null,
                    'token_expires_at' => null
                ]);
            }
        }

        return $this->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get token from header
     *
     * @return string|null
     */
    private function getTokenFromHeader()
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }
}

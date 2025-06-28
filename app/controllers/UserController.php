<?php

namespace App\Controllers;

use System\Controller;
use System\Session;

/**
 * User Controller
 *
 * Example controller for user operations
 */
class UserController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Load user model
        $this->loadModel('User');
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        // Redirect to login if not logged in
        if (!Session::has('user_id')) {
            $this->redirect('/user/login');
        }

        // Get user data
        $userId = Session::get('user_id');
        $user = $this->model->findById($userId);

        $data = [
            'title' => 'User Profile',
            'user' => $user
        ];

        $this->view('user/profile', $data);
    }

    /**
     * Login page
     *
     * @return void
     */
    public function login()
    {
        // Check if already logged in
        if (Session::has('user_id')) {
            $this->redirect('/user');
        }

        $data = [
            'title' => 'Login'
        ];

        // Process form submission
        if ($this->request->method() === 'POST') {
            $username = $this->request->input('username');
            $password = $this->request->input('password');

            // Validate input
            if (empty($username) || empty($password)) {
                $data['error'] = 'Username and password are required';
            } else {
                // Check user credentials
                $user = $this->model->findByUsername($username);

                if ($user && $this->model->verifyPassword($password, $user['password'])) {
                    // Set session
                    Session::set('user_id', $user['id']);
                    Session::set('username', $user['username']);

                    // Redirect to profile
                    $this->redirect('/user');
                } else {
                    $data['error'] = 'Invalid username or password';
                }
            }
        }

        $this->view('user/login', $data);
    }

    /**
     * Register page
     *
     * @return void
     */
    public function register()
    {
        // Check if already logged in
        if (Session::has('user_id')) {
            $this->redirect('/user');
        }

        $data = [
            'title' => 'Register'
        ];

        // Process form submission
        if ($this->request->method() === 'POST') {
            $username = $this->request->input('username');
            $email = $this->request->input('email');
            $password = $this->request->input('password');
            $confirmPassword = $this->request->input('confirm_password');

            // Validate input
            if (empty($username) || empty($email) || empty($password)) {
                $data['error'] = 'All fields are required';
            } elseif ($password !== $confirmPassword) {
                $data['error'] = 'Passwords do not match';
            } elseif ($this->model->findByUsername($username)) {
                $data['error'] = 'Username already exists';
            } elseif ($this->model->findByEmail($email)) {
                $data['error'] = 'Email already exists';
            } else {
                // Create user
                $userData = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if ($this->model->createUser($userData)) {
                    Session::setFlash('success', 'Registration successful, please login');
                    $this->redirect('/user/login');
                } else {
                    $data['error'] = 'Failed to create user';
                }
            }
        }

        $this->view('user/register', $data);
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        // Destroy session
        Session::destroy();

        // Redirect to login page
        $this->redirect('/user/login');
    }
}

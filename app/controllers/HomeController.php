<?php

namespace App\Controllers;

use System\Controller;

/**
 * Home Controller
 *
 * Default controller for the application
 */
class HomeController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Set default layout for all methods
        $this->setLayout('layouts.app');
    }

    /**
     * Index method
     *
     * Default method for the controller
     *
     * @return void
     */
    public function index()
    {
        $data = [
            'message' => 'Framework siap digunakan!'
        ];

        $this->view('home.index', $data);
    }

    /**
     * About page
     *
     * @return void
     */
    public function about()
    {
        $this->view('home.about', [
            'version' => '1.0.0',
            'author' => 'Syafiq Muhammad Kahfi'
        ]);
    }

    /**
     * Contact page
     *
     * @return void
     */
    public function contact()
    {
        $this->view('home.contact');
    }

    /**
     * Handle contact form submission
     *
     * @return void
     */
    public function contactSubmit()
    {
        // Get form data
        $name = $this->request->post('name');
        $email = $this->request->post('email');
        $subject = $this->request->post('subject');
        $message = $this->request->post('message');

        // Validate data (basic validation)
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            // Set flash message
            $this->session->setFlashMessage('error', 'All fields are required');
            // Redirect back to contact form
            return $this->response->redirect(route('contact'));
        }

        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->setFlashMessage('error', 'Invalid email format');
            return $this->response->redirect(route('contact'));
        }

        // TODO: In a real application, you would send an email here
        // For now, we'll just set a success message

        // Set flash message for success
        $this->session->setFlashMessage('success', 'Your message has been sent! We will get back to you soon.');

        // Redirect back to contact page
        return $this->response->redirect(route('contact'));
    }
}

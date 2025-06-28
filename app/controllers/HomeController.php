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
     * Index method
     *
     * Default method for the controller
     *
     * @return void
     */
    public function index()
    {
        $data = [
            'title' => 'Welcome to Simple PHP MVC Framework',
            'message' => 'Framework siap digunakan!'
        ];

        $this->view('home/index', $data);
    }
}

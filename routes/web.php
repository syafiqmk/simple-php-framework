<?php

/**
 * Web Routes
         // Routes yang memerlukan autentikasi
        Route::middleware('auth', function() {
            Route::get('/profile', 'UserController@profile')->setName('profile');
            Route::get('/view/{id}', 'UserController@view')->setName('view');
            Route::get('/edit/{id}', 'UserController@edit')->setName('edit');
            Route::post('/update/{id}', 'UserController@update')->setName('update');
            Route::delete('/delete/{id}', 'UserController@delete')->setName('delete');efinisikan semua route untuk aplikasi web di sini
 */

use System\Route;

// Home routes
Route::get('/', 'HomeController@index')->setName('home');
Route::get('/about', 'HomeController@about')->setName('about');
Route::get('/contact', 'HomeController@contact')->setName('contact');

// User routes dengan grup, prefix, dan middleware
Route::prefix('user', function () {
    Route::name('user.', function () {
        // Rute publik
        Route::get('/', 'UserController@index')->setName('index');
        Route::get('/login', 'UserController@login')->setName('login');
        Route::post('/login', 'UserController@authenticate')->setName('authenticate');
        Route::get('/register', 'UserController@register')->setName('register');
        Route::post('/register', 'UserController@store')->setName('store');
        Route::get('/logout', 'UserController@logout')->setName('logout');

        // Routes yang memerlukan autentikasi
        Route::middleware('auth', function () {
            Route::get('/profile', 'UserController@profile')->setName('profile');
            Route::get('/view/{id}', 'UserController@view')->setName('view');
            Route::get('/edit/{id}', 'UserController@edit')->setName('edit');
            Route::post('/update/{id}', 'UserController@update')->setName('update');
            Route::delete('/delete/{id}', 'UserController@delete')->setName('delete');
        });
    });
});

/**
 * Contoh resource routing (RESTful)
 * Uncomment jika diperlukan:
 *
 * Route::resource('photos', 'PhotoController');
 *
 * Ini akan membuat 7 route: 
 * - GET    /photos            - index
 * - GET    /photos/create     - create
 * - POST   /photos            - store
 * - GET    /photos/{id}       - show
 * - GET    /photos/{id}/edit  - edit
 * - PUT    /photos/{id}       - update
 * - DELETE /photos/{id}       - destroy
 */

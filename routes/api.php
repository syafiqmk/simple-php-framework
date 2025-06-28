<?php

/**
 * API Routes
 * 
 * Definisikan semua route untuk API di sini
 */

use System\Route;

// API version 1
Route::prefix('api/v1', function () {
    Route::name('api.', function () {
        // Auth routes
        Route::post('/login', 'Api\AuthController@login')->setName('auth.login');
        Route::post('/register', 'Api\AuthController@register')->setName('auth.register');
        Route::get('/logout', 'Api\AuthController@logout')->middleware('auth')->setName('auth.logout');

        // User routes with authentication middleware
        Route::middleware('auth', function () {
            // RESTful API untuk users
            Route::get('/users', 'Api\UserController@index')->setName('users.index');
            Route::get('/users/{id}', 'Api\UserController@show')->setName('users.show');
            Route::post('/users', 'Api\UserController@store')->setName('users.store');
            Route::put('/users/{id}', 'Api\UserController@update')->setName('users.update');
            Route::delete('/users/{id}', 'Api\UserController@delete')->setName('users.delete');

            /**
             * Alternatif menggunakan resource route:
             * Route::resource('users', 'Api\UserController', [
             *     'only' => ['index', 'show', 'store', 'update', 'destroy']
             * ]);
             */
        });
    });
});

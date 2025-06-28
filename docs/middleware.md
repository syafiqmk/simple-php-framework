# Middleware

Middleware menyediakan mekanisme untuk memeriksa dan memfilter HTTP request yang masuk ke aplikasi. Middleware bisa digunakan untuk:

- Autentikasi
- Verifikasi CSRF token
- Logging
- Dan banyak lagi

## Built-in Middleware

Framework ini menyertakan beberapa middleware built-in:

- **CsrfMiddleware**: Melindungi aplikasi dari serangan CSRF (Cross-Site Request Forgery) dengan memverifikasi token pada form POST
- **AuthMiddleware**: Memverifikasi bahwa user sudah login untuk mengakses rute tertentu

## Menggunakan Middleware pada Route

Anda dapat menerapkan middleware pada route individual:

```php
Route::get('profile', 'UserController@profile')->middleware('auth');
```

Atau pada grup route:

```php
Route::middleware('auth', function() {
    Route::get('dashboard', 'DashboardController@index');
    Route::get('settings', 'UserController@settings');
});
```

## Membuat Custom Middleware

Buat kelas middleware di direktori `system/Middleware` atau `app/Middleware`:

```php
<?php

namespace App\Middleware;

use System\Middleware\MiddlewareInterface;
use System\Request;

class CustomMiddleware implements MiddlewareInterface
{
    /**
     * Handle middleware
     *
     * @param Request $request
     * @param callable $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        // Lakukan sesuatu sebelum request diproses
        
        // Lanjutkan ke middleware berikutnya atau ke controller
        $response = $next($request);
        
        // Lakukan sesuatu setelah request diproses
        
        return $response;
    }
}
```

## Mendaftarkan Middleware

Daftarkan middleware di Bootstrap.php:

```php
private function registerMiddleware()
{
    // Register middleware global
    $this->router->registerGlobalMiddleware('App\Middleware\GlobalMiddleware');
    
    // Register middleware bernama
    $this->router->registerMiddleware('custom', 'App\Middleware\CustomMiddleware');
}
```

## CSRF Protection

CSRF protection diaktifkan secara otomatis untuk semua request POST, PUT, dan DELETE. Pada form, tambahkan CSRF token field:

```php
<form method="POST" action="/post/create">
    <?= csrf_field() ?>
    <!-- form fields -->
</form>
```

Fungsi `csrf_field()` akan menambahkan hidden input dengan token CSRF.

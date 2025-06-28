# Sistem Routing

Framework ini menyediakan sistem routing yang fleksibel dan mudah digunakan, terinspirasi oleh framework Laravel.

## Dasar-Dasar Routing

Semua route didefinisikan dalam file-file di direktori `/routes`:
- `web.php`: untuk route yang diakses melalui web browser
- `api.php`: untuk route API

### Route Dasar

```php
Route::get('hello', function() {
    echo 'Hello World';
});

Route::post('user/create', 'UserController@store');
```

### HTTP Methods

```php
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::patch($uri, $callback);
Route::delete($uri, $callback);
Route::any($uri, $callback); // Merespons semua HTTP method
```

## Route Parameter

```php
Route::get('user/{id}', function($id) {
    echo "User ID: $id";
});

Route::get('user/{id}/profile', 'UserController@profile');
```

## Named Routes

```php
Route::get('user/profile', 'UserController@profile')->setName('profile');

// Di view/controller, Anda bisa menggunakan:
$url = route('profile');
```

## Route Groups

### Prefix Group

```php
Route::prefix('admin', function() {
    Route::get('users', 'AdminController@users');     // Akan menjadi /admin/users
    Route::get('settings', 'AdminController@settings'); // Akan menjadi /admin/settings
});
```

### Middleware Group

```php
Route::middleware('auth', function() {
    Route::get('dashboard', 'DashboardController@index');
    Route::get('profile', 'UserController@profile');
});
```

### Nama Group

```php
Route::name('admin.', function() {
    Route::get('users', 'AdminController@users')->setName('users'); // Nama menjadi 'admin.users'
});
```

### Kombinasi Group

```php
Route::prefix('admin', function() {
    Route::name('admin.', function() {
        Route::middleware('auth', function() {
            Route::get('dashboard', 'DashboardController@index')->setName('dashboard');
        });
    });
});
```

## Resource Routes

Untuk membuat route CRUD secara cepat:

```php
Route::resource('photos', 'PhotoController');
```

Ini akan membuat 7 route yang sesuai dengan tindakan resource:

| Method | URI                | Action  | Route Name      |
|--------|-------------------|---------|----------------|
| GET    | photos            | index   | photos.index   |
| GET    | photos/create     | create  | photos.create  |
| POST   | photos            | store   | photos.store   |
| GET    | photos/{id}       | show    | photos.show    |
| GET    | photos/{id}/edit  | edit    | photos.edit    |
| PUT    | photos/{id}       | update  | photos.update  |
| DELETE | photos/{id}       | destroy | photos.destroy |

### Resource Routes Sebagian

```php
Route::resource('photos', 'PhotoController', ['only' => ['index', 'show']]);
Route::resource('photos', 'PhotoController', ['except' => ['destroy']]);
```

## Helper Functions

### Menghasilkan URL

```php
// Menghasilkan URL untuk named route
$url = route('user.profile', ['id' => 1]);

// URL dasar
$url = url('about');

// URL untuk asset
$url = asset('css/app.css');
```

### Redirect

```php
// Redirect ke named route
redirect_to('home');

// Redirect ke named route dengan parameter
redirect_to('user.profile', ['id' => 1]);

// Redirect ke halaman sebelumnya
back();
```

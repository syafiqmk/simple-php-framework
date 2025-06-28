# Simple PHP MVC Framework

[![GitHub license](https://img.shields.io/github/license/syafiqmk/simple-php-framework)](https://github.com/syafiqmk/simple-php-framework/blob/main/LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/syafiqmk/simple-php-framework)](https://github.com/syafiqmk/simple-php-framework/issues)
[![GitHub stars](https://img.shields.io/github/stars/syafiqmk/simple-php-framework)](https://github.com/syafiqmk/simple-php-framework/stargazers)

Framework PHP sederhana dengan arsitektur Model-View-Controller (MVC) yang mudah digunakan dan memiliki performa optimal. Dilengkapi dengan command line interface (CLI) untuk mempermudah development.

## Command Line Interface (CLI)

Framework ini dilengkapi dengan Command Line Interface (CLI) bernama "Flash" yang terinspirasi dari Laravel Artisan dan CodeIgniter Spark. CLI ini memudahkan pengembangan dengan menyediakan perintah-perintah untuk membuat controller, model, dan lainnya.

### Penggunaan Dasar

```bash
# Melihat daftar perintah yang tersedia
php bin/flash help

# Menjalankan server development
php bin/flash serve

# Membuat controller baru
php bin/flash make:controller NamaController

# Membuat model baru
php bin/flash make:model NamaModel

# Menjalankan migrasi database
php bin/flash migrate

# Membuat perintah kustom
php bin/flash make:command NamaPerintah
```

### Membuat Perintah Kustom

Anda dapat membuat perintah kustom dengan menggunakan:

```bash
php bin/flash make:command NamaPerintah
```

Perintah di atas akan membuat file command baru di `app/Console/Commands/`. Setelah membuat command, Anda perlu mendaftarkannya di `app/Console/CommandServiceProvider.php`:

```php
// app/Console/CommandServiceProvider.php
public static function register($runner)
{
    // Register all custom commands here
    $runner->registerCommand(new Commands\NamaPerintahCommand());
}
```

## Struktur Framework

```
/app
  /controllers   - Berisi semua controller
  /models        - Berisi semua model
  /views         - Berisi semua view
/bin             - Berisi script-script command line
/config          - Berisi file konfigurasi
/database
  /migrations    - Berisi file-file migrasi database
/public          - Direktori publik yang berisi file index.php sebagai entry point
/routes          - Berisi file definisi routing web dan API
/system          - Berisi file inti framework
/tests           - Berisi skrip dan file untuk testing
/docs            - Berisi dokumentasi framework
```

## Fitur

- Routing modern mirip Laravel dengan file khusus untuk route
- Named routes dan route generation
- Route grouping, prefixing, dan middleware
- Resource routing untuk RESTful API
- Routing API melalui `/routes/api.php`
- Template engine dengan layout, section, dan includes
- Autoloading kelas (PSR-4)
- ORM dasar untuk database
- Pemisahan tampilan yang jelas dengan MVC
- Session management
- Request dan Response handling
- Command line tools: migrate, serve, dan testing
- Database migrations
- Development server built-in
- Pretty URL dengan .htaccess
- Error handling

## Dokumentasi

- [Routing](docs/routing.md)
- [Middleware](docs/middleware.md)
- [Sessions](docs/sessions.md)
- [Views](docs/views.md)

_Dokumentasi lainnya dalam pengembangan._

## Instalasi

1. Clone repositori ini ke direktori web server Anda:
   ```
   git clone https://github.com/syafiqmk/simple-php-framework.git
   ```
   
2. Pindah ke direktori proyek:
   ```
   cd simple-php-framework
   ```
   
3. Rename file `htaccess.txt` menjadi `.htaccess` jika server mendukung:
   ```
   mv htaccess.txt .htaccess
   ```
   
4. Konfigurasikan pengaturan database dan aplikasi di `/config/config.php`

5. Jalankan migrasi database dengan perintah:
   ```
   php bin/migrate.php run
   ```
   
6. Jalankan development server dengan perintah:
   ```
   php bin/serve.php
   ```
   
7. Akses aplikasi melalui browser di `http://localhost:8000`

## Penggunaan Development Server

```
php bin/serve.php [port] [host]
```

Port default adalah 8000 dan host default adalah localhost jika tidak disebutkan.

## Menjalankan Tests

Framework ini memiliki system testing sederhana untuk menguji komponen-komponen utama:

```
php bin/test.php all           # Jalankan semua tests
php bin/test.php router        # Jalankan hanya router tests
php bin/test.php view          # Jalankan hanya view engine tests
php bin/test.php middleware    # Jalankan hanya middleware tests
```

## Database Migrations

Framework ini menyediakan tool command line untuk mengelola migrasi database:

```
php bin/migrate.php run                  # Jalankan semua migrasi yang belum diaplikasikan
php bin/migrate.php reset                # Reset semua migrasi
php bin/migrate.php rollback [steps]     # Rollback migrasi terakhir atau sejumlah steps
php bin/migrate.php create <name>        # Buat file migrasi baru
```

## Cara Penggunaan

### Controller

Buat controller baru di direktori `/app/controllers` dengan format berikut:

```php
<?php
namespace App\Controllers;

use System\Controller;

class NamaController extends Controller
{
    public function index()
    {
        // Akses request data
        $input = $this->request->input('nama');
        
        // Logika controller
        $data = [
            'title' => 'Judul Halaman'
        ];
        
        // Tampilkan view
        $this->view('nama/index', $data);
        
        // Atau kembalikan JSON response
        // $this->json(['status' => 'success', 'data' => $result]);
    }
    
    public function lainnya()
    {
        // Method lainnya
    }
}
```

### Model

Buat model baru di direktori `/app/models` dengan format berikut:

```php
<?php
namespace App\Models;

use System\Model;

class NamaModel extends Model
{
    protected $table = 'nama_tabel';
    
    public function getAll()
    {
        return $this->findAll();
    }
    
    public function getById($id)
    {
        return $this->findById($id);
    }
    
    // Method lainnya...
}
```

### View

Buat view baru di direktori `/app/views/nama_controller/nama_view.php`:

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
</head>
<body>
    <h1><?= $title ?></h1>
    <p>Konten halaman...</p>
</body>
</html>
```

### Session Management

```php
// Set session value
\System\Session::set('key', 'value');

// Get session value
$value = \System\Session::get('key');

// Check if session key exists
if (\System\Session::has('key')) {
    // Do something
}

// Remove session key
\System\Session::remove('key');

// Flash messages
\System\Session::setFlash('success', 'Operation completed successfully');
$message = \System\Session::getFlash('success');
```

### Request and Response

```php
// Get all input data
$allInputs = $this->request->all();

// Get specific input
$name = $this->request->input('name');

// Check if input exists
if ($this->request->has('email')) {
    // Do something
}

// Get only specific inputs
$credentials = $this->request->only(['username', 'password']);

// Get request method
$method = $this->request->method();

// JSON Response
$this->json(['status' => 'success', 'data' => $result]);

// Redirect
$this->redirect('/other/url');
```

## Routing

Routing mengikuti pola: `http://domain.com/controller/method/param1/param2`

- Default controller: `Home`
- Default method: `index`

### Daftar Route

Berikut adalah daftar route yang tersedia di aplikasi:

```
GET     /               HomeController@index
GET     /about          AboutController@index
POST    /contact         ContactController@submit
GET     /users          UserController@index
GET     /users/{id}    UserController@show
```

### Membuat Route Baru

Untuk membuat route baru, buka file `routes/web.php` dan tambahkan kode berikut:

```php
$router->get('/new-route', 'NewController@index');
```

### Route dengan Parameter

Untuk route yang memerlukan parameter, gunakan tanda kurung kurawal:

```php
$router->get('/users/{id}', 'UserController@show');
```

### Named Routes

Anda juga dapat memberikan nama pada route untuk memudahkan pengalihan:

```php
$router->get('/profile', 'UserController@profile')->name('profile');
```

Untuk mengalihkan ke named route:

```php
$this->redirectRoute('profile');
```

### Route Grouping

Untuk mengelompokkan route, gunakan method `group`:

```php
$router->group('/admin', function() {
    $router->get('/dashboard', 'AdminController@dashboard');
    $router->get('/settings', 'AdminController@settings');
});
```

### Middleware

Untuk menambahkan middleware pada route, gunakan method `middleware`:

```php
$router->get('/profile', 'UserController@profile')->middleware('auth');
```

## Kontribusi

Kontribusi sangat diterima! Jika Anda ingin berkontribusi:

1. Fork repositori ini
2. Buat branch fitur (`git checkout -b feature/amazing-feature`)
3. Commit perubahan Anda (`git commit -m 'Add some amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buka Pull Request

## Lisensi

Didistribusikan di bawah Lisensi MIT. Lihat `LICENSE` untuk informasi lebih lanjut.

## Kontak

Syafiq Muhammad Kahfi - [@syafiqmk](https://github.com/syafiqmk)

Link Proyek: [https://github.com/syafiqmk/simple-php-framework](https://github.com/syafiqmk/simple-php-framework)

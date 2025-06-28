# Simple PHP MVC Framework

[![GitHub license](https://img.shields.io/github/license/syafiqmk/simple-php-framework)](https://github.com/syafiqmk/simple-php-framework/blob/main/LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/syafiqmk/simple-php-framework)](https://github.com/syafiqmk/simple-php-framework/issues)
[![GitHub stars](https://img.shields.io/github/stars/syafiqmk/simple-php-framework)](https://github.com/syafiqmk/simple-php-framework/stargazers)

Framework PHP sederhana dengan arsitektur Model-View-Controller (MVC) yang mudah digunakan dan memiliki performa optimal.

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
/system          - Berisi file inti framework
```

## Fitur

- Routing sederhana dan otomatis
- Autoloading kelas
- ORM dasar untuk database
- Pemisahan tampilan yang jelas dengan MVC
- Session management
- Request dan Response handling
- Database migrations
- Development server
- Pretty URL dengan .htaccess
- Error handling

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
php bin/serve.php [port]
```

Port default adalah 8000 jika tidak disebutkan.

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

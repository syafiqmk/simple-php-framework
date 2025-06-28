# Template Engine

Framework ini menyediakan templating engine yang sederhana namun powerful, terinspirasi oleh Blade dan sistem templating modern. Templating engine memudahkan pengembang untuk membuat dan mengelola view dengan fitur-fitur seperti layout, section, dan includes.

## Dasar Penggunaan

### Layouts dan Sections

Template engine memungkinkan Anda membuat layout dan section untuk memisahkan bagian-bagian dari halaman Anda.

#### Membuat Layout

Buat file layout di `app/views/layouts`, contohnya `app.php`:

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= yields('title', 'Default Title') ?></title>
    <?= yields('styles') ?>
</head>
<body>
    <header>
        <h1>My Application</h1>
    </header>
    
    <main>
        <?= yields('content') ?>
    </main>
    
    <footer>
        <p>&copy; <?= date('Y') ?> My Application</p>
    </footer>
    
    <?= yields('scripts') ?>
</body>
</html>
```

#### Menggunakan Layout

Di view Anda, gunakan layout dengan syntax berikut:

```php
<?php extend('layouts.app') ?>

<?php section('title') ?>
    Home Page
<?php endSection() ?>

<?php section('content') ?>
    <h1>Welcome to my website</h1>
    <p>This is the home page content</p>
<?php endSection() ?>

<?php section('scripts') ?>
    <script>
        console.log('Home page loaded!');
    </script>
<?php endSection() ?>
```

### Menggunakan View di Controller

```php
class HomeController extends Controller
{
    public function __construct()
    {
        // Set layout default untuk semua metode di controller ini
        $this->setLayout('layouts.app');
    }
    
    public function index()
    {
        // Render view dengan data
        return $this->view('home.index', [
            'message' => 'Hello World'
        ]);
        
        // Atau override layout
        // return $this->view('home.index', ['message' => 'Hello'], 'layouts.admin');
    }
}
```

## API Templating

### Helpers

Framework ini menyediakan beberapa helper untuk memudahkan penggunaan template:

#### `extend($layout)`

Menggunakan layout untuk view saat ini.

```php
<?php extend('layouts.app') ?>
```

#### `section($name)` dan `endSection()`

Mendefinisikan section yang bisa ditempatkan di layout.

```php
<?php section('content') ?>
    <h1>Content here</h1>
<?php endSection() ?>
```

#### `yields($name, $default = '')`

Menampilkan content dari section tertentu.

```php
<?= yields('content', 'Default content') ?>
```

#### `includeView($view, $data = [])`

Menyertakan subview ke dalam view.

```php
<?= includeView('partials.header', ['title' => 'My Page']) ?>
```

#### `e($string)`

HTML escaping untuk mencegah XSS.

```php
<p><?= e($userInput) ?></p>
```

### Menggunakan View di Response

```php
// Di controller
return $this->response->html(
    View::render('user.profile', ['user' => $user])
);

// Atau langsung
return view('user.profile', ['user' => $user]);
```

### Passing Data ke Semua View

```php
// Di Bootstrap atau service provider
View::share('siteName', 'My Awesome Site');
View::share([
    'version' => '1.0.0',
    'environment' => 'production'
]);
```

## Best Practices

1. Gunakan layout untuk komponen berulang seperti header, footer, sidebar
2. Pisahkan UI kecil ke dalam partial views menggunakan `includeView()`
3. Selalu gunakan helper `e()` untuk output dari input user
4. Gunakan `yields()` dengan default value untuk menangani section yang mungkin tidak ada
5. Untuk views yang kompleks, gunakan subtemplate dengan `includeView()`

# Session Management

Framework ini menyediakan mekanisme session management yang sederhana namun powerful untuk menyimpan data user antara request HTTP.

## Menggunakan Session

### Menyimpan Data ke Session

```php
// Static method
Session::set('key', 'value');

// Di controller (setelah injeksi)
$this->session->setValue('key', 'value');
```

### Mengambil Data dari Session

```php
// Static method
$value = Session::get('key', 'default');

// Di controller
$value = $this->session->getValue('key', 'default');
```

### Memeriksa Keberadaan Data

```php
// Static method
if (Session::has('key')) {
    // Key exists
}

// Di controller
if ($this->session->hasKey('key')) {
    // Key exists
}
```

### Menghapus Data dari Session

```php
// Static method
Session::remove('key');

// Di controller
$this->session->removeKey('key');
```

## Flash Messages

Flash messages adalah pesan sementara yang hanya bertahan untuk satu request berikutnya. Ini sangat berguna untuk menampilkan pesan sukses atau error setelah redirect.

### Menyimpan Flash Message

```php
// Static method
Session::setFlash('success', 'Data berhasil disimpan');
Session::setFlash('error', 'Terjadi kesalahan');

// Di controller
$this->session->setFlashMessage('success', 'Data berhasil disimpan');
$this->session->setFlashMessage('error', 'Terjadi kesalahan');
```

### Menampilkan Flash Message di View

```php
<!-- Periksa dan tampilkan flash message -->
<?php if(has_flash('success')): ?>
    <div class="alert alert-success">
        <?= e(get_flash('success')) ?>
    </div>
<?php endif; ?>

<?php if(has_flash('error')): ?>
    <div class="alert alert-danger">
        <?= e(get_flash('error')) ?>
    </div>
<?php endif; ?>
```

## Helper Functions

Framework ini menyediakan beberapa helper function untuk session:

- `has_flash($type)`: Memeriksa apakah flash message ada
- `get_flash($type, $default = '')`: Mendapatkan dan menghapus flash message
- `csrf_field()`: Menyisipkan CSRF token field dalam form

## CSRF Protection

Semua form POST dilindungi secara otomatis dari serangan CSRF. Pastikan untuk menyisipkan CSRF token field di setiap form:

```php
<form method="POST" action="/post/create">
    <?= csrf_field() ?>
    <!-- form fields -->
</form>
```

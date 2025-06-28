<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #35424a;
            color: #fff;
            padding: 30px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        .content {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .highlight {
            background-color: #e0f7fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }

        footer {
            background-color: #35424a;
            color: #fff;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1><?= $title ?></h1>
        </div>
    </header>

    <div class="container">
        <div class="content">
            <h2>Selamat Datang di Framework PHP MVC Sederhana</h2>
            <p>Ini adalah halaman awal framework PHP MVC sederhana yang telah berhasil dijalankan.</p>

            <div class="highlight">
                <h3>Status Framework</h3>
                <p><?= $message ?></p>
            </div>

            <h3>Struktur Framework</h3>
            <p>Framework ini menggunakan pola arsitektur Model-View-Controller (MVC) dengan struktur berikut:</p>
            <ul>
                <li><strong>app/controllers/</strong> - Berisi semua controller</li>
                <li><strong>app/models/</strong> - Berisi semua model</li>
                <li><strong>app/views/</strong> - Berisi semua view</li>
                <li><strong>config/</strong> - Berisi file konfigurasi</li>
                <li><strong>public/</strong> - Direktori publik yang berisi file index.php sebagai entry point</li>
                <li><strong>system/</strong> - Berisi file inti framework</li>
            </ul>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Simple PHP MVC Framework</p>
        </div>
    </footer>
</body>

</html>
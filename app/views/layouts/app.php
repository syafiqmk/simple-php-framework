<?php

use System\Session;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= yields('title', 'Simple PHP Framework') ?></title>

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">

    <!-- Additional styles -->
    <?= yields('styles') ?>
</head>

<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="container">
                <a href="<?= route('home') ?>" class="brand">Simple PHP Framework</a>
                <ul class="nav-links">
                    <li><a href="<?= route('home') ?>">Home</a></li>
                    <li><a href="<?= route('about') ?>">About</a></li>
                    <li><a href="<?= route('contact') ?>">Contact</a></li>
                    <?php if (Session::has('user_id')): ?>
                        <li><a href="<?= route('user.profile') ?>">Profile</a></li>
                        <li><a href="<?= route('user.logout') ?>">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?= route('user.login') ?>">Login</a></li>
                        <li><a href="<?= route('user.register') ?>">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            <?php if (has_flash('success')): ?>
                <div class="alert alert-success">
                    <?= e(get_flash('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (has_flash('error')): ?>
                <div class="alert alert-danger">
                    <?= e(get_flash('error')) ?>
                </div>
            <?php endif; ?>

            <!-- Page content -->
            <?= yields('content') ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Simple PHP Framework. MIT License.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?= asset('js/app.js') ?>"></script>

    <!-- Additional scripts -->
    <?= yields('scripts') ?>
</body>

</html>
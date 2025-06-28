<?php extend('layouts.app') ?>

<?php section('title') ?>
About - Simple PHP Framework
<?php endSection() ?>

<?php section('content') ?>
<div class="card">
    <h1>About Simple PHP Framework</h1>

    <p class="mb-3">
        Simple PHP Framework adalah framework PHP minimalis namun powerful yang dibangun dengan fokus pada:
    </p>

    <ul class="mb-3">
        <li>Performa optimal</li>
        <li>Kesederhanaan penggunaan</li>
        <li>Arsitektur MVC yang bersih</li>
        <li>Sistem routing modern</li>
        <li>Template engine yang fleksibel</li>
    </ul>

    <h2>Versi</h2>
    <p>Current version: <?= e($version) ?></p>

    <h2>Author</h2>
    <p><?= e($author) ?></p>

    <h2>License</h2>
    <p>Framework ini dilisensikan di bawah MIT License.</p>
</div>
<?php endSection() ?>
<?php extend('layouts.app') ?>

<?php section('title') ?>
500 - Server Error
<?php endSection() ?>

<?php section('content') ?>
<div class="text-center py-3">
    <h1 style="font-size: 72px; margin-bottom: 0; color: #dc2626;">500</h1>
    <h2>Internal Server Error</h2>

    <p class="mb-3">Sorry, something went wrong on our server. We are working to fix the issue.</p>

    <a href="<?= route('home') ?>" class="btn btn-primary">Return to Homepage</a>
</div>

<?php endSection() ?>
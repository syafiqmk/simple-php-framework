<?php extend('layouts.app') ?>

<?php section('title') ?>
404 - Page Not Found
<?php endSection() ?>

<?php section('content') ?>
<div class="text-center py-3">
    <h1 style="font-size: 72px; margin-bottom: 0;">404</h1>
    <h2>Page Not Found</h2>

    <p class="mb-3">The page you are looking for might have been removed or is temporarily unavailable.</p>

    <a href="<?= route('home') ?>" class="btn btn-primary">Return to Homepage</a>
</div>

<?php endSection() ?>
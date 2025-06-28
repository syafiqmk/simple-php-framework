<?php extend('layouts.app') ?>

<?php section('title') ?>
Home - Simple PHP Framework
<?php endSection() ?>

<?php section('content') ?>
<div class="jumbotron">
    <h1>Welcome to Simple PHP Framework</h1>
    <p>A lightweight yet powerful PHP MVC framework for building modern web applications.</p>
</div>

<div class="features">
    <div class="feature-card">
        <h2>Modern Routing</h2>
        <p>Feature-rich routing system inspired by Laravel with named routes, groups, and middleware support.</p>
    </div>

    <div class="feature-card">
        <h2>Template Engine</h2>
        <p>Simple but powerful template engine with layouts, sections, and inclusion.</p>
    </div>

    <div class="feature-card">
        <h2>MVC Architecture</h2>
        <p>Clear separation of concerns with Models, Views, and Controllers.</p>
    </div>
</div>
<?php endSection() ?>

<?php section('scripts') ?>
<script>
    console.log('Welcome to Simple PHP Framework!');
</script>
<?php endSection() ?>
<?php extend('layouts.app') ?>

<?php section('title') ?>
Register - Simple PHP Framework
<?php endSection() ?>

<?php section('content') ?>
<div class="auth-form">
    <h1>Register</h1>

    <form method="POST" action="<?= route('user.store') ?>">
        <?= csrf_field() ?>

        <?php if (has_flash('error')): ?>
            <div class="alert alert-danger">
                <?= e(get_flash('error')) ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Register</button>
        </div>

        <p class="mt-3">
            Already have an account? <a href="<?= route('user.login') ?>">Login</a>
        </p>
    </form>
</div>
<?php endSection() ?>
<?php extend('layouts.app') ?>

<?php section('title') ?>
Login - Simple PHP Framework
<?php endSection() ?>

<?php section('content') ?>
<div class="auth-form">
    <h1>Login</h1>

    <form method="POST" action="<?= route('user.authenticate') ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>

        <p class="mt-3">
            Don't have an account? <a href="<?= route('user.register') ?>">Register</a>
        </p>
    </form>
</div>
<?php endSection() ?>
<p>&copy; <?= date('Y') ?> Simple PHP MVC Framework</p>
</div>
</footer>
</body>

</html>
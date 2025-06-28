<?php extend('layouts.app') ?>

<?php section('title') ?>
User Profile - Simple PHP Framework
<?php endSection() ?>

<?php section('content') ?>
<div class="card">
    <h1>User Profile</h1>

    <?php if (has_flash('success')): ?>
        <div class="alert alert-success">
            <?= e(get_flash('success')) ?>
        </div>
    <?php endif; ?>

    <div class="profile-details">
        <div class="form-group">
            <label>Username:</label>
            <div class="form-control-static"><?= e($user['username'] ?? 'N/A') ?></div>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <div class="form-control-static"><?= e($user['email'] ?? 'N/A') ?></div>
        </div>

        <div class="form-group">
            <label>Member Since:</label>
            <div class="form-control-static">
                <?= $user['created_at'] ? date('F j, Y', strtotime($user['created_at'])) : 'N/A' ?>
            </div>
        </div>
    </div>

    <div class="profile-actions">
        <a href="<?= route('user.edit', ['id' => $user['id'] ?? 0]) ?>" class="btn btn-primary">Edit Profile</a>
        
        <form method="POST" action="<?= route('user.delete', ['id' => $user['id'] ?? 0]) ?>" class="d-inline" 
              onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-danger">Delete Account</button>
        </form>
    </div>
</div>
<?php endSection() ?>

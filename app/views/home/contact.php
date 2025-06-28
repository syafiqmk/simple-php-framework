<?php extend('layouts.app') ?>

<?php section('title') ?>
Contact - Simple PHP Framework
<?php endSection() ?>

<?php section('content') ?>
<div class="card">
    <h1>Contact Us</h1>

    <p class="mb-3">
        Have questions about Simple PHP Framework? Feel free to contact us!
    </p>

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

    <form method="POST" action="<?= route('contact.submit') ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" name="subject" id="subject" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Send Message</button>
        </div>
    </form>
</div>
<?php endSection() ?>
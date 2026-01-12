<?= $this->include('templates/header') ?>

<h1>Create Account</h1>

<form method="post" action="<?= site_url('register') ?>" class="mt-2" style="max-width: 500px;">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" value="<?= old('first_name') ?>" required>
        <?php if (isset($validation) && $validation->hasError('first_name')): ?>
            <div class="error"><?= $validation->getError('first_name') ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" value="<?= old('last_name') ?>" required>
        <?php if (isset($validation) && $validation->hasError('last_name')): ?>
            <div class="error"><?= $validation->getError('last_name') ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= old('email') ?>" required>
        <?php if (isset($validation) && $validation->hasError('email')): ?>
            <div class="error"><?= $validation->getError('email') ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <?php if (isset($validation) && $validation->hasError('password')): ?>
            <div class="error"><?= $validation->getError('password') ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="password_confirm">Confirm Password</label>
        <input type="password" name="password_confirm" id="password_confirm" required>
        <?php if (isset($validation) && $validation->hasError('password_confirm')): ?>
            <div class="error"><?= $validation->getError('password_confirm') ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
    <p class="mt-1">Already have an account? <a href="/login">Login here</a></p>
</form>

<?= $this->include('templates/footer') ?>

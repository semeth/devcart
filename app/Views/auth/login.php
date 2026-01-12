<?= $this->include('templates/header') ?>

<h1>Login</h1>

<form method="post" action="<?= site_url('login') ?>" class="mt-2" style="max-width: 500px;">
    <?= csrf_field() ?>
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

    <button type="submit" class="btn btn-primary">Login</button>
    <p class="mt-1">
        <a href="/forgot-password">Forgot Password?</a> | 
        <a href="/register">Create Account</a>
    </p>
</form>

<?= $this->include('templates/footer') ?>

<?= $this->include('templates/header') ?>

<h1>Forgot Password</h1>

<form method="post" action="/forgot-password" class="mt-2" style="max-width: 500px;">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" value="<?= old('email') ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Send Reset Link</button>
    <p class="mt-1"><a href="/login">Back to Login</a></p>
</form>

<?= $this->include('templates/footer') ?>

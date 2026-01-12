<?= $this->include('templates/header') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Forgot Password</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= site_url('forgot-password') ?>">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0"><a href="<?= site_url('login') ?>">Back to Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>

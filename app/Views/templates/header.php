<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'DevCart - eCommerce Platform') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('/') ?>">DevCart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('products') ?>">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('categories') ?>">Categories</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('cart') ?>">
                            Cart <span class="badge bg-secondary" id="cart-count">0</span>
                        </a>
                    </li>
                    <?php if (session()->has('user_id')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('orders') ?>">My Orders</a>
                        </li>
                        <?php if (session()->get('user_role') === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= site_url('admin') ?>">Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('logout') ?>">Logout (<?= esc(session()->get('user_name')) ?>)</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('register') ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container">
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success"><?= esc(session('success')) ?></div>
            <?php endif; ?>
            
            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger"><?= esc(session('error')) ?></div>
            <?php endif; ?>

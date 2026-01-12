<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'DevCart - eCommerce Platform') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="navbar-brand">
                    <a href="/" style="text-decoration: none;">DevCart</a>
                </div>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/products">Products</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/categories">Categories</a></li>
                    <?php if (session()->has('user_id')): ?>
                        <li class="nav-item"><a class="nav-link active" href="/cart">Cart <span class="badge text-bg-secondary" id="cart-count">0</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="/orders">My Orders</a></li>
                        <?php if (session()->get('user_role') === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link active" href="/admin">Admin</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link active" href="/logout">Logout (<?= esc(session()->get('user_name')) ?>)</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link active" href="/cart">Cart <span class="cart-count" id="cart-count">0</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="/login">Login</a></li>
                        <li class="nav-item"><a class="nav-link active" href="/register">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success"><?= esc(session('success')) ?></div>
            <?php endif; ?>
            
            <?php if (session()->has('error')): ?>
                <div class="alert alert-error"><?= esc(session('error')) ?></div>
            <?php endif; ?>

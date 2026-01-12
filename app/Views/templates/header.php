<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'DevCart - eCommerce Platform') ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        header { background: #2c3e50; color: white; padding: 1rem 0; }
        nav { display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 2rem; list-style: none; }
        .nav-links a { color: white; text-decoration: none; }
        .nav-links a:hover { text-decoration: underline; }
        .cart-count { background: #e74c3c; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; margin-left: 5px; }
        main { min-height: calc(100vh - 200px); padding: 2rem 0; }
        footer { background: #34495e; color: white; text-align: center; padding: 2rem 0; margin-top: 3rem; }
        .alert { padding: 1rem; margin: 1rem 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn { display: inline-block; padding: 0.5rem 1.5rem; background: #3498db; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .btn-primary { background: #3498db; }
        .btn-success { background: #27ae60; }
        .btn-danger { background: #e74c3c; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }
        .form-group textarea { min-height: 100px; }
        .error { color: #e74c3c; font-size: 0.9rem; margin-top: 0.25rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem; }
        .card { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .card img { width: 100%; height: 200px; object-fit: cover; }
        .card-body { padding: 1rem; }
        .card-title { font-size: 1.2rem; margin-bottom: 0.5rem; }
        .card-price { font-size: 1.5rem; color: #e74c3c; font-weight: bold; }
        .text-center { text-align: center; }
        .mt-1 { margin-top: 1rem; }
        .mt-2 { margin-top: 2rem; }
        .mb-1 { margin-bottom: 1rem; }
        .mb-2 { margin-bottom: 2rem; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <a href="/" style="color: white; text-decoration: none;">DevCart</a>
                </div>
                <ul class="nav-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/products">Products</a></li>
                    <li><a href="/categories">Categories</a></li>
                    <?php if (session()->has('user_id')): ?>
                        <li><a href="/cart">Cart <span class="cart-count" id="cart-count">0</span></a></li>
                        <li><a href="/orders">My Orders</a></li>
                        <?php if (session()->get('user_role') === 'admin'): ?>
                            <li><a href="/admin">Admin</a></li>
                        <?php endif; ?>
                        <li><a href="/logout">Logout (<?= esc(session()->get('user_name')) ?>)</a></li>
                    <?php else: ?>
                        <li><a href="/cart">Cart <span class="cart-count" id="cart-count">0</span></a></li>
                        <li><a href="/login">Login</a></li>
                        <li><a href="/register">Register</a></li>
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

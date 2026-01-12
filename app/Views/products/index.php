<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <h1 class="mb-4">Products</h1>

    <form method="get" action="<?= site_url('products/search') ?>" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="q" placeholder="Search products..." value="<?= esc($search ?? '') ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php if (isset($category)): ?>
        <div class="alert alert-info">
            <h4 class="alert-heading">Category: <?= esc($category['name']) ?></h4>
            <?php if ($category['description']): ?>
                <p class="mb-0"><?= esc($category['description']) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <div class="alert alert-warning">
            <p class="mb-0">No products found.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100">
                        <?php if ($product['image']): ?>
                            <img src="<?= esc($product['image']) ?>" class="card-img-top" alt="<?= esc($product['name']) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="<?= site_url('products/' . $product['slug']) ?>" class="text-decoration-none text-dark">
                                    <?= esc($product['name']) ?>
                                </a>
                            </h5>
                            <div class="mb-2">
                                <span class="h5 text-danger">$<?= number_format($product['price'], 2) ?></span>
                                <?php if ($product['compare_at_price'] && $product['compare_at_price'] > $product['price']): ?>
                                    <small class="text-muted text-decoration-line-through ms-2">$<?= number_format($product['compare_at_price'], 2) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="mb-2">
                                <?php if ($product['stock_status'] === 'in_stock'): ?>
                                    <span class="badge bg-success">In Stock</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Out of Stock</span>
                                <?php endif; ?>
                            </div>
                            <form method="post" action="<?= site_url('cart/add') ?>" class="mt-auto">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary w-100" <?= $product['stock_status'] !== 'in_stock' ? 'disabled' : '' ?>>
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->include('templates/footer') ?>

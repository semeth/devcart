<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <?php if ($product['image']): ?>
                <img src="<?= esc($product['image']) ?>" class="img-fluid rounded" alt="<?= esc($product['name']) ?>">
            <?php else: ?>
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                    <span class="text-muted">No Image</span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($product['gallery'])): ?>
                <div class="row g-2 mt-2">
                    <?php foreach ($product['gallery'] as $image): ?>
                        <div class="col-3">
                            <img src="<?= esc($image) ?>" class="img-thumbnail" alt="">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <h1 class="mb-3"><?= esc($product['name']) ?></h1>
            <div class="mb-3">
                <span class="h3 text-danger">$<?= number_format($product['price'], 2) ?></span>
                <?php if ($product['compare_at_price'] && $product['compare_at_price'] > $product['price']): ?>
                    <small class="text-muted text-decoration-line-through ms-2">$<?= number_format($product['compare_at_price'], 2) ?></small>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <?php if ($product['stock_status'] === 'in_stock'): ?>
                    <span class="badge bg-success fs-6">✓ In Stock</span>
                <?php else: ?>
                    <span class="badge bg-danger fs-6">✗ Out of Stock</span>
                <?php endif; ?>
            </div>

            <?php if ($product['short_description']): ?>
                <p class="lead"><?= esc($product['short_description']) ?></p>
            <?php endif; ?>

            <form method="post" action="<?= site_url('cart/add') ?>" class="mb-4">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-auto">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?? 999 ?>" required style="width: 100px;">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-lg" <?= $product['stock_status'] !== 'in_stock' ? 'disabled' : '' ?>>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </form>

            <?php if ($product['description']): ?>
                <div class="mt-4">
                    <h2>Description</h2>
                    <p class="text-muted"><?= nl2br(esc($product['description'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($relatedProducts)): ?>
        <div class="mt-5">
            <h2 class="mb-4">Related Products</h2>
            <div class="row g-4">
                <?php foreach ($relatedProducts as $related): ?>
                    <div class="col-md-3">
                        <div class="card h-100">
                            <?php if ($related['image']): ?>
                                <img src="<?= esc($related['image']) ?>" class="card-img-top" alt="<?= esc($related['name']) ?>" style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="<?= site_url('products/' . $related['slug']) ?>" class="text-decoration-none text-dark">
                                        <?= esc($related['name']) ?>
                                    </a>
                                </h5>
                                <p class="card-text text-danger fw-bold">$<?= number_format($related['price'], 2) ?></p>
                                <a href="<?= site_url('products/' . $related['slug']) ?>" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->include('templates/footer') ?>

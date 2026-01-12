<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <div class="jumbotron bg-light p-5 rounded mb-5">
        <h1 class="display-4">Welcome to DevCart</h1>
        <p class="lead">Your one-stop shop for all your needs!</p>
    </div>

    <?php if (!empty($featuredProducts)): ?>
        <h2 class="mb-4">Featured Products</h2>
        <div class="row g-4 mb-5">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100">
                        <?php if ($product['image']): ?>
                            <img src="<?= base_url($product['image']) ?>" class="card-img-top" alt="<?= esc($product['name']) ?>" style="height: 200px; object-fit: cover;">
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
                            <p class="card-text text-danger fw-bold">$<?= number_format($product['price'], 2) ?></p>
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

    <?php if (!empty($categories)): ?>
        <h2 class="mb-4">Shop by Category</h2>
        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100">
                        <?php if ($category['image']): ?>
                            <img src="<?= base_url($category['image']) ?>" class="card-img-top" alt="<?= esc($category['name']) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="<?= site_url('categories/' . $category['slug']) ?>" class="text-decoration-none text-dark">
                                    <?= esc($category['name']) ?>
                                </a>
                            </h5>
                            <a href="<?= site_url('categories/' . $category['slug']) ?>" class="btn btn-primary mt-auto">Browse</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->include('templates/footer') ?>

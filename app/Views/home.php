<?= $this->include('templates/header') ?>

<h1>Welcome to DevCart</h1>
<p>Your one-stop shop for all your needs!</p>

<?php if (!empty($featuredProducts)): ?>
    <h2 style="margin-top: 3rem;">Featured Products</h2>
    <div class="grid" style="margin-top: 1rem;">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="card">
                <?php if ($product['image']): ?>
                    <img src="<?= esc($product['image']) ?>" alt="<?= esc($product['name']) ?>">
                <?php else: ?>
                    <div style="height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">No Image</div>
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-title">
                        <a href="/products/<?= esc($product['slug']) ?>" style="text-decoration: none; color: inherit;">
                            <?= esc($product['name']) ?>
                        </a>
                    </h3>
                    <p class="card-price">$<?= number_format($product['price'], 2) ?></p>
                    <form method="post" action="/cart/add" style="margin-top: 1rem;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary" <?= $product['stock_status'] !== 'in_stock' ? 'disabled' : '' ?>>
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($categories)): ?>
    <h2 style="margin-top: 3rem;">Shop by Category</h2>
    <div class="grid" style="margin-top: 1rem;">
        <?php foreach ($categories as $category): ?>
            <div class="card">
                <?php if ($category['image']): ?>
                    <img src="<?= esc($category['image']) ?>" alt="<?= esc($category['name']) ?>">
                <?php else: ?>
                    <div style="height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">No Image</div>
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-title">
                        <a href="/categories/<?= esc($category['slug']) ?>" style="text-decoration: none; color: inherit;">
                            <?= esc($category['name']) ?>
                        </a>
                    </h3>
                    <a href="/categories/<?= esc($category['slug']) ?>" class="btn btn-primary">Browse</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->include('templates/footer') ?>

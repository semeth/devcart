<?= $this->include('templates/header') ?>

<h1>Products</h1>

<form method="get" action="/products/search" class="mb-2" style="max-width: 500px;">
    <div class="form-group" style="display: flex; gap: 0.5rem;">
        <input type="text" name="q" placeholder="Search products..." value="<?= esc($search ?? '') ?>" style="flex: 1;">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

<?php if (isset($category)): ?>
    <h2>Category: <?= esc($category['name']) ?></h2>
    <?php if ($category['description']): ?>
        <p><?= esc($category['description']) ?></p>
    <?php endif; ?>
<?php endif; ?>

<?php if (empty($products)): ?>
    <p>No products found.</p>
<?php else: ?>
    <div class="grid">
        <?php foreach ($products as $product): ?>
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
                    <?php if ($product['compare_at_price'] && $product['compare_at_price'] > $product['price']): ?>
                        <p style="text-decoration: line-through; color: #999;">$<?= number_format($product['compare_at_price'], 2) ?></p>
                    <?php endif; ?>
                    <p style="margin-top: 0.5rem;">
                        <?php if ($product['stock_status'] === 'in_stock'): ?>
                            <span style="color: #27ae60;">In Stock</span>
                        <?php else: ?>
                            <span style="color: #e74c3c;">Out of Stock</span>
                        <?php endif; ?>
                    </p>
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

<?= $this->include('templates/footer') ?>

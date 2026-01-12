<?= $this->include('templates/header') ?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
    <div>
        <?php if ($product['image']): ?>
            <img src="<?= esc($product['image']) ?>" alt="<?= esc($product['name']) ?>" style="width: 100%; border-radius: 8px;">
        <?php else: ?>
            <div style="height: 400px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 8px;">No Image</div>
        <?php endif; ?>
        
        <?php if (!empty($product['gallery'])): ?>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; margin-top: 1rem;">
                <?php foreach ($product['gallery'] as $image): ?>
                    <img src="<?= esc($image) ?>" alt="" style="width: 100%; height: 80px; object-fit: cover; border-radius: 4px;">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div>
        <h1><?= esc($product['name']) ?></h1>
        <p style="font-size: 2rem; color: #e74c3c; font-weight: bold; margin: 1rem 0;">
            $<?= number_format($product['price'], 2) ?>
        </p>
        <?php if ($product['compare_at_price'] && $product['compare_at_price'] > $product['price']): ?>
            <p style="text-decoration: line-through; color: #999;">$<?= number_format($product['compare_at_price'], 2) ?></p>
        <?php endif; ?>

        <p style="margin: 1rem 0;">
            <?php if ($product['stock_status'] === 'in_stock'): ?>
                <span style="color: #27ae60; font-weight: bold;">✓ In Stock</span>
            <?php else: ?>
                <span style="color: #e74c3c; font-weight: bold;">✗ Out of Stock</span>
            <?php endif; ?>
        </p>

        <?php if ($product['short_description']): ?>
            <p style="margin: 1rem 0;"><?= esc($product['short_description']) ?></p>
        <?php endif; ?>

        <form method="post" action="/cart/add" style="margin: 2rem 0;">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <div class="form-group" style="max-width: 200px;">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?? 999 ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" style="font-size: 1.2rem; padding: 0.75rem 2rem;" <?= $product['stock_status'] !== 'in_stock' ? 'disabled' : '' ?>>
                Add to Cart
            </button>
        </form>

        <?php if ($product['description']): ?>
            <div style="margin-top: 2rem;">
                <h2>Description</h2>
                <p><?= nl2br(esc($product['description'])) ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($relatedProducts)): ?>
    <div style="margin-top: 3rem;">
        <h2>Related Products</h2>
        <div class="grid">
            <?php foreach ($relatedProducts as $related): ?>
                <div class="card">
                    <?php if ($related['image']): ?>
                        <img src="<?= esc($related['image']) ?>" alt="<?= esc($related['name']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="/products/<?= esc($related['slug']) ?>" style="text-decoration: none; color: inherit;">
                                <?= esc($related['name']) ?>
                            </a>
                        </h3>
                        <p class="card-price">$<?= number_format($related['price'], 2) ?></p>
                        <a href="/products/<?= esc($related['slug']) ?>" class="btn btn-primary">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->include('templates/footer') ?>

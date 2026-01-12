<?= $this->include('templates/header') ?>

<?php if ($parentCategory): ?>
    <p><a href="/categories/<?= esc($parentCategory['slug']) ?>">← Back to <?= esc($parentCategory['name']) ?></a></p>
<?php endif; ?>

<h1><?= esc($category['name']) ?></h1>

<?php if ($category['description']): ?>
    <p><?= esc($category['description']) ?></p>
<?php endif; ?>

<?php if (!empty($childCategories)): ?>
    <div style="margin: 2rem 0;">
        <h2>Subcategories</h2>
        <div class="grid">
            <?php foreach ($childCategories as $child): ?>
                <div class="card">
                    <?php if ($child['image']): ?>
                        <img src="<?= esc($child['image']) ?>" alt="<?= esc($child['name']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h3><a href="/categories/<?= esc($child['slug']) ?>"><?= esc($child['name']) ?></a></h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (empty($products)): ?>
    <p>No products in this category.</p>
<?php else: ?>
    <h2>Products</h2>
    <div class="grid" style="margin-top: 1rem;">
        <?php foreach ($products as $product): ?>
            <div class="card">
                <?php if ($product['image']): ?>
                    <img src="<?= esc($product['image']) ?>" alt="<?= esc($product['name']) ?>">
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

<?= $this->include('templates/footer') ?>

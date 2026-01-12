<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <?php if ($parentCategory): ?>
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url('categories') ?>">Categories</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('categories/' . $parentCategory['slug']) ?>"><?= esc($parentCategory['name']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= esc($category['name']) ?></li>
            </ol>
        </nav>
    <?php endif; ?>

    <h1 class="mb-3"><?= esc($category['name']) ?></h1>

    <?php if ($category['description']): ?>
        <p class="lead"><?= esc($category['description']) ?></p>
    <?php endif; ?>

    <?php if (!empty($childCategories)): ?>
        <div class="mb-5">
            <h2 class="mb-4">Subcategories</h2>
            <div class="row g-4">
                <?php foreach ($childCategories as $child): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100">
                            <?php if ($child['image']): ?>
                                <img src="<?= base_url($child['image']) ?>" class="card-img-top" alt="<?= esc($child['name']) ?>" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span class="text-muted">No Image</span>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="<?= site_url('categories/' . $child['slug']) ?>" class="text-decoration-none text-dark">
                                        <?= esc($child['name']) ?>
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No products in this category.</p>
        </div>
    <?php else: ?>
        <h2 class="mb-4">Products</h2>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
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
</div>

<?= $this->include('templates/footer') ?>

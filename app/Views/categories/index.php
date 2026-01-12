<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <h1 class="mb-4">Categories</h1>

    <?php if (empty($categories)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No categories found.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100">
                        <?php if ($category['image']): ?>
                            <img src="<?= esc($category['image']) ?>" class="card-img-top" alt="<?= esc($category['name']) ?>" style="height: 200px; object-fit: cover;">
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
                            <?php if ($category['description']): ?>
                                <p class="card-text text-muted"><?= esc(substr($category['description'], 0, 100)) ?>...</p>
                            <?php endif; ?>
                            <a href="<?= site_url('categories/' . $category['slug']) ?>" class="btn btn-primary mt-auto">View Products</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->include('templates/footer') ?>

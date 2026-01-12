<?= $this->include('templates/header') ?>

<h1>Categories</h1>

<?php if (empty($categories)): ?>
    <p>No categories found.</p>
<?php else: ?>
    <div class="grid" style="margin-top: 2rem;">
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
                    <?php if ($category['description']): ?>
                        <p><?= esc(substr($category['description'], 0, 100)) ?>...</p>
                    <?php endif; ?>
                    <a href="/categories/<?= esc($category['slug']) ?>" class="btn btn-primary">View Products</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->include('templates/footer') ?>

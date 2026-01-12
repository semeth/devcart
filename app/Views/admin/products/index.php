<?= $this->include('admin/templates/header') ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Products</h1>
        <a href="<?= site_url('admin/products/new') ?>" class="btn btn-primary">
            <span class="icon">➕</span> Add New Product
        </a>
    </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No products found. <a href="<?= site_url('admin/products/new') ?>">Create your first product</a></p>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= esc($product['name']) ?></td>
                                    <td><code><?= esc($product['sku']) ?></code></td>
                                    <td>$<?= number_format($product['price'], 2) ?></td>
                                    <td><?= $product['stock_quantity'] ?></td>
                                    <td>
                                        <?php if ($product['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('admin/products/edit/' . $product['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?= site_url('admin/products/delete/' . $product['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

</div>

<?= $this->include('admin/templates/footer') ?>

<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Categories</h1>
        <a href="<?= site_url('admin/categories/new') ?>" class="btn btn-primary">Add Category</a>
    </div>

    <a href="<?= site_url('admin') ?>" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <?php if (empty($categories)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No categories found. <a href="<?= site_url('admin/categories/new') ?>">Create your first category</a></p>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Parent</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= esc($category['name']) ?></td>
                                    <td><code><?= esc($category['slug']) ?></code></td>
                                    <td>
                                        <?php if (!empty($category['parent_name'])): ?>
                                            <?= esc($category['parent_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($category['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('admin/categories/edit/' . $category['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?= site_url('admin/categories/delete/' . $category['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
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

<?= $this->include('templates/footer') ?>

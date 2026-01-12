<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><?= $category ? 'Edit Category' : 'Add New Category' ?></h1>
            
            <a href="<?= site_url('admin/categories') ?>" class="btn btn-secondary mb-3">← Back to Categories</a>

            <form method="post" action="<?= site_url('admin/categories/save' . ($category ? '/' . $category['id'] : '')) ?>">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $category['name'] ?? '') ?>" required>
                                    <?php if (isset($validation) && $validation->hasError('name')): ?>
                                        <div class="text-danger small"><?= $validation->getError('name') ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug *</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="<?= old('slug', $category['slug'] ?? '') ?>" required>
                                    <small class="form-text text-muted">URL-friendly version of the name (e.g., "electronics")</small>
                                    <?php if (isset($validation) && $validation->hasError('slug')): ?>
                                        <div class="text-danger small"><?= $validation->getError('slug') ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">Parent Category</label>
                                    <select class="form-select" id="parent_id" name="parent_id">
                                        <option value="">-- Top Level (No Parent) --</option>
                                        <?php foreach ($parentOptions as $parent): ?>
                                            <option value="<?= $parent['id'] ?>" <?= old('parent_id', $category['parent_id'] ?? '') == $parent['id'] ? 'selected' : '' ?>>
                                                <?= esc($parent['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted">Leave empty for top-level category</small>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5"><?= old('description', $category['description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">SEO</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" value="<?= old('meta_title', $category['meta_title'] ?? '') ?>">
                                    <small class="form-text text-muted">SEO title for search engines</small>
                                </div>
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3"><?= old('meta_description', $category['meta_description'] ?? '') ?></textarea>
                                    <small class="form-text text-muted">SEO description for search engines</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Category Image URL</label>
                                    <input type="text" class="form-control" id="image" name="image" value="<?= old('image', $category['image'] ?? '') ?>" placeholder="https://example.com/image.jpg">
                                </div>

                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= old('sort_order', $category['sort_order'] ?? 0) ?>">
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', $category['is_active'] ?? 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100"><?= $category ? 'Update Category' : 'Create Category' ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>

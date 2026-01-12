<?= $this->include('admin/templates/header') ?>

<div class="admin-content">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><?= $category ? 'Edit Category' : 'Add New Category' ?></h1>

            <form method="post" action="<?= site_url('admin/categories/save' . ($category ? '/' . $category['id'] : '')) ?>" enctype="multipart/form-data">
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
                                    <label for="image" class="form-label">Category Image</label>
                                    <?php if (!empty($category['image'])): ?>
                                        <div class="mb-2">
                                            <img src="<?= base_url($category['image']) ?>" alt="Current image" class="img-thumbnail" style="max-height: 150px;">
                                            <p class="small text-muted mt-1">Current image</p>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Upload a new image or leave empty to keep current image. Allowed: JPG, PNG, GIF, WebP</small>
                                    <?php if (isset($validation) && $validation->hasError('image')): ?>
                                        <div class="text-danger small"><?= $validation->getError('image') ?></div>
                                    <?php endif; ?>
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

</div>

<?= $this->include('admin/templates/footer') ?>

<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><?= $product ? 'Edit Product' : 'Add New Product' ?></h1>
            
            <a href="<?= site_url('admin/products') ?>" class="btn btn-secondary mb-3">← Back to Products</a>

            <form method="post" action="<?= site_url('admin/products/save' . ($product ? '/' . $product['id'] : '')) ?>">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $product['name'] ?? '') ?>" required>
                                    <?php if (isset($validation) && $validation->hasError('name')): ?>
                                        <div class="text-danger small"><?= $validation->getError('name') ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug *</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="<?= old('slug', $product['slug'] ?? '') ?>" required>
                                    <small class="form-text text-muted">URL-friendly version of the name (e.g., "my-product")</small>
                                    <?php if (isset($validation) && $validation->hasError('slug')): ?>
                                        <div class="text-danger small"><?= $validation->getError('slug') ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU *</label>
                                    <input type="text" class="form-control" id="sku" name="sku" value="<?= old('sku', $product['sku'] ?? '') ?>" required>
                                    <?php if (isset($validation) && $validation->hasError('sku')): ?>
                                        <div class="text-danger small"><?= $validation->getError('sku') ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">-- Select Category --</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" <?= old('category_id', $product['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                                <?= esc($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Short Description</label>
                                    <textarea class="form-control" id="short_description" name="short_description" rows="2"><?= old('short_description', $product['short_description'] ?? '') ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5"><?= old('description', $product['description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Pricing</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="price" class="form-label">Price *</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= old('price', $product['price'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="compare_at_price" class="form-label">Compare at Price</label>
                                        <input type="number" step="0.01" class="form-control" id="compare_at_price" name="compare_at_price" value="<?= old('compare_at_price', $product['compare_at_price'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="cost_price" class="form-label">Cost Price</label>
                                        <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price" value="<?= old('cost_price', $product['cost_price'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Inventory</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="<?= old('stock_quantity', $product['stock_quantity'] ?? 0) ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="stock_status" class="form-label">Stock Status</label>
                                        <select class="form-select" id="stock_status" name="stock_status">
                                            <option value="in_stock" <?= old('stock_status', $product['stock_status'] ?? 'in_stock') == 'in_stock' ? 'selected' : '' ?>>In Stock</option>
                                            <option value="out_of_stock" <?= old('stock_status', $product['stock_status'] ?? '') == 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                                            <option value="on_backorder" <?= old('stock_status', $product['stock_status'] ?? '') == 'on_backorder' ? 'selected' : '' ?>>On Backorder</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="manage_stock" name="manage_stock" value="1" <?= old('manage_stock', $product['manage_stock'] ?? 1) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="manage_stock">
                                                Manage Stock
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Shipping</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="weight" class="form-label">Weight (kg)</label>
                                        <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="<?= old('weight', $product['weight'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="dimensions" class="form-label">Dimensions (LxWxH in cm)</label>
                                        <input type="text" class="form-control" id="dimensions" name="dimensions" value="<?= old('dimensions', $product['dimensions'] ?? '') ?>" placeholder="10x5x2">
                                    </div>
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
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" value="<?= old('meta_title', $product['meta_title'] ?? '') ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2"><?= old('meta_description', $product['meta_description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Publish</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Product Image URL</label>
                                    <input type="text" class="form-control" id="image" name="image" value="<?= old('image', $product['image'] ?? '') ?>" placeholder="https://example.com/image.jpg">
                                </div>

                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= old('sort_order', $product['sort_order'] ?? 0) ?>">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', $product['is_active'] ?? 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?= old('is_featured', $product['is_featured'] ?? 0) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_featured">
                                            Featured
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100"><?= $product ? 'Update Product' : 'Create Product' ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>

<?= $this->include('admin/templates/header') ?>

<div class="admin-content">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Configure: <?= esc($extension['name']) ?></h1>
            

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($extension['description']): ?>
                <div class="alert alert-info">
                    <?= esc($extension['description']) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('admin/extensions/' . $extension['type'] . '/' . $extension['code'] . '/configure') ?>">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Configuration Settings</h5>
                            </div>
                            <div class="card-body">
                                <!-- Active Status -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= $extension['is_active'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            <strong>Enable this extension</strong>
                                        </label>
                                    </div>
                                </div>

                                <hr>

                                <!-- Dynamic Settings Fields -->
                                <?php foreach ($schema as $field): ?>
                                    <?php
                                    $key = $field['key'] ?? '';
                                    $label = $field['label'] ?? $key;
                                    $type = $field['type'] ?? 'text';
                                    $required = $field['required'] ?? false;
                                    $description = $field['description'] ?? '';
                                    $default = $field['default'] ?? '';
                                    $value = old($key, $settings[$key] ?? $default);
                                    ?>
                                    
                                    <?php if ($type === 'boolean'): ?>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="<?= esc($key) ?>" name="<?= esc($key) ?>" value="1" <?= $value ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="<?= esc($key) ?>">
                                                    <strong><?= esc($label) ?></strong>
                                                </label>
                                            </div>
                                            <?php if ($description): ?>
                                                <small class="form-text text-muted"><?= esc($description) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif ($type === 'textarea'): ?>
                                        <div class="mb-3">
                                            <label for="<?= esc($key) ?>" class="form-label">
                                                <?= esc($label) ?>
                                                <?php if ($required): ?><span class="text-danger">*</span><?php endif; ?>
                                            </label>
                                            <textarea class="form-control" id="<?= esc($key) ?>" name="<?= esc($key) ?>" rows="<?= $field['rows'] ?? 3 ?>" <?= $required ? 'required' : '' ?>><?= esc($value) ?></textarea>
                                            <?php if ($description): ?>
                                                <small class="form-text text-muted"><?= esc($description) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif ($type === 'number' || $type === 'decimal'): ?>
                                        <div class="mb-3">
                                            <label for="<?= esc($key) ?>" class="form-label">
                                                <?= esc($label) ?>
                                                <?php if ($required): ?><span class="text-danger">*</span><?php endif; ?>
                                            </label>
                                            <input type="number" class="form-control" id="<?= esc($key) ?>" name="<?= esc($key) ?>" value="<?= esc($value) ?>" 
                                                   step="<?= $field['step'] ?? ($type === 'decimal' ? '0.01' : '1') ?>"
                                                   min="<?= $field['min'] ?? '' ?>"
                                                   max="<?= $field['max'] ?? '' ?>"
                                                   <?= $required ? 'required' : '' ?>>
                                            <?php if ($description): ?>
                                                <small class="form-text text-muted"><?= esc($description) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="mb-3">
                                            <label for="<?= esc($key) ?>" class="form-label">
                                                <?= esc($label) ?>
                                                <?php if ($required): ?><span class="text-danger">*</span><?php endif; ?>
                                            </label>
                                            <input type="<?= esc($type) ?>" class="form-control" id="<?= esc($key) ?>" name="<?= esc($key) ?>" value="<?= esc($value) ?>" <?= $required ? 'required' : '' ?>>
                                            <?php if ($description): ?>
                                                <small class="form-text text-muted"><?= esc($description) ?></small>
                                            <?php endif; ?>
                                            <?php if (isset($field['encrypted']) && $field['encrypted']): ?>
                                                <small class="form-text text-warning">This value will be encrypted</small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Extension Information</h5>
                            </div>
                            <div class="card-body">
                                <dl>
                                    <dt>Type</dt>
                                    <dd><?= esc(ucfirst($extension['type'])) ?></dd>
                                    
                                    <dt>Code</dt>
                                    <dd><code><?= esc($extension['code']) ?></code></dd>
                                    
                                    <dt>Version</dt>
                                    <dd><?= esc($extension['version']) ?></dd>
                                    
                                    <dt>Status</dt>
                                    <dd>
                                        <?php if ($extension['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save Configuration</button>
                    <a href="<?= site_url('admin/extensions') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<?= $this->include('admin/templates/footer') ?>

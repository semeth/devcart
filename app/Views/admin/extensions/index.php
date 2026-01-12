<?= $this->include('admin/templates/header') ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Extensions</h1>
    </div>


    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Type Filter -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $currentType === 'all' ? 'active' : '' ?>" href="<?= site_url('admin/extensions?type=all') ?>">All</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $currentType === 'payment' ? 'active' : '' ?>" href="<?= site_url('admin/extensions?type=payment') ?>">Payment Methods</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $currentType === 'shipping' ? 'active' : '' ?>" href="<?= site_url('admin/extensions?type=shipping') ?>">Shipping Methods</a>
        </li>
    </ul>

    <!-- Payment Extensions -->
    <?php if (isset($extensions['payment']) && !empty($extensions['payment'])): ?>
        <div class="mb-5">
            <h2 class="mb-3">Payment Methods</h2>
            <div class="row">
                <?php foreach ($extensions['payment'] as $extension): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0"><?= esc($extension['name']) ?></h5>
                                    <?php if ($extension['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </div>
                                <p class="card-text text-muted small"><?= esc($extension['description'] ?? '') ?></p>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/extensions/' . $extension['type'] . '/' . $extension['code'] . '/configure') ?>" class="btn btn-primary btn-sm">
                                        Configure
                                    </a>
                                    <a href="<?= site_url('admin/extensions/' . $extension['type'] . '/' . $extension['code'] . '/toggle') ?>" class="btn btn-sm <?= $extension['is_active'] ? 'btn-warning' : 'btn-success' ?>">
                                        <?= $extension['is_active'] ? 'Disable' : 'Enable' ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Shipping Extensions -->
    <?php if (isset($extensions['shipping']) && !empty($extensions['shipping'])): ?>
        <div class="mb-5">
            <h2 class="mb-3">Shipping Methods</h2>
            <div class="row">
                <?php foreach ($extensions['shipping'] as $extension): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0"><?= esc($extension['name']) ?></h5>
                                    <?php if ($extension['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </div>
                                <p class="card-text text-muted small"><?= esc($extension['description'] ?? '') ?></p>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/extensions/' . $extension['type'] . '/' . $extension['code'] . '/configure') ?>" class="btn btn-primary btn-sm">
                                        Configure
                                    </a>
                                    <a href="<?= site_url('admin/extensions/' . $extension['type'] . '/' . $extension['code'] . '/toggle') ?>" class="btn btn-sm <?= $extension['is_active'] ? 'btn-warning' : 'btn-success' ?>">
                                        <?= $extension['is_active'] ? 'Disable' : 'Enable' ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($extensions['payment'] ?? []) && empty($extensions['shipping'] ?? [])): ?>
        <div class="alert alert-info">
            <p class="mb-0">No extensions found. Extensions will appear here once they are registered in the database.</p>
        </div>
    <?php endif; ?>
</div>

</div>

<?= $this->include('admin/templates/footer') ?>

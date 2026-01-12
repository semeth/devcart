<?= $this->include('admin/templates/header') ?>

<div class="admin-content">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="text-primary fs-2 fw-bold mb-1"><?= $stats['total_products'] ?></p>
                    <small class="text-muted">Active: <?= $stats['active_products'] ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="text-success fs-2 fw-bold mb-1"><?= $stats['total_orders'] ?></p>
                    <small class="text-muted">Pending: <?= $stats['pending_orders'] ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="text-danger fs-2 fw-bold mb-1"><?= $stats['total_users'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="text-warning fs-2 fw-bold mb-1">$<?= number_format($stats['total_revenue'], 2) ?></p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mb-4">Recent Orders</h2>
    <?php if (empty($recentOrders)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No recent orders.</p>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td><?= esc($order['order_number']) ?></td>
                                    <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                    <td><span class="badge bg-primary"><?= esc(ucfirst($order['status'])) ?></span></td>
                                    <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?= $this->include('admin/templates/footer') ?>

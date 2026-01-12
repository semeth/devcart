<?= $this->include('templates/header') ?>

<h1>Admin Dashboard</h1>

<div class="grid" style="margin-top: 2rem;">
    <div class="card">
        <div class="card-body">
            <h3>Total Products</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #3498db;"><?= $stats['total_products'] ?></p>
            <p><small>Active: <?= $stats['active_products'] ?></small></p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3>Total Orders</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #27ae60;"><?= $stats['total_orders'] ?></p>
            <p><small>Pending: <?= $stats['pending_orders'] ?></small></p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3>Total Users</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #e74c3c;"><?= $stats['total_users'] ?></p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3>Total Revenue</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #9b59b6;">$<?= number_format($stats['total_revenue'], 2) ?></p>
        </div>
    </div>
</div>

<h2 style="margin-top: 3rem;">Recent Orders</h2>
<?php if (empty($recentOrders)): ?>
    <p>No recent orders.</p>
<?php else: ?>
    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd;">
                <th style="padding: 1rem; text-align: left;">Order Number</th>
                <th style="padding: 1rem; text-align: left;">Date</th>
                <th style="padding: 1rem; text-align: left;">Status</th>
                <th style="padding: 1rem; text-align: left;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentOrders as $order): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 1rem;"><?= esc($order['order_number']) ?></td>
                    <td style="padding: 1rem;"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                    <td style="padding: 1rem;"><?= esc(ucfirst($order['status'])) ?></td>
                    <td style="padding: 1rem;">$<?= number_format($order['total_amount'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div style="margin-top: 2rem;">
    <a href="/admin/products" class="btn btn-primary">Manage Products</a>
    <a href="/admin/categories" class="btn btn-primary">Manage Categories</a>
    <a href="/admin/orders" class="btn btn-primary">Manage Orders</a>
</div>

<?= $this->include('templates/footer') ?>

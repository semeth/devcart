<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <h1 class="mb-4">Manage Orders</h1>

    <a href="<?= site_url('admin') ?>" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No orders found.</p>
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
                                <th>Payment Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= esc($order['order_number']) ?></td>
                                    <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                    <td><span class="badge bg-primary"><?= esc(ucfirst($order['status'])) ?></span></td>
                                    <td><span class="badge bg-success"><?= esc(ucfirst($order['payment_status'])) ?></span></td>
                                    <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                    <td>
                                        <a href="<?= site_url('admin/orders/' . $order['order_number']) ?>" class="btn btn-sm btn-primary">View</a>
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

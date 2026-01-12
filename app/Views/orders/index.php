<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <h1 class="mb-4">My Orders</h1>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            <p class="mb-3">You have no orders yet.</p>
            <a href="<?= site_url('products') ?>" class="btn btn-primary">Start Shopping</a>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong><?= esc($order['order_number']) ?></strong></td>
                                    <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?= esc(ucfirst($order['status'])) ?></span>
                                    </td>
                                    <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                    <td>
                                        <a href="<?= site_url('orders/' . $order['order_number']) ?>" class="btn btn-sm btn-primary">View</a>
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

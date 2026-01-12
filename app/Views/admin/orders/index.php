<?= $this->include('templates/header') ?>

<h1>Manage Orders</h1>

<a href="/admin" class="btn">Back to Dashboard</a>

<?php if (empty($orders)): ?>
    <p>No orders found.</p>
<?php else: ?>
    <table style="width: 100%; border-collapse: collapse; margin-top: 2rem;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd;">
                <th style="padding: 1rem; text-align: left;">Order Number</th>
                <th style="padding: 1rem; text-align: left;">Date</th>
                <th style="padding: 1rem; text-align: left;">Status</th>
                <th style="padding: 1rem; text-align: left;">Payment Status</th>
                <th style="padding: 1rem; text-align: left;">Total</th>
                <th style="padding: 1rem; text-align: left;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 1rem;"><?= esc($order['order_number']) ?></td>
                    <td style="padding: 1rem;"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                    <td style="padding: 1rem;"><?= esc(ucfirst($order['status'])) ?></td>
                    <td style="padding: 1rem;"><?= esc(ucfirst($order['payment_status'])) ?></td>
                    <td style="padding: 1rem;">$<?= number_format($order['total_amount'], 2) ?></td>
                    <td style="padding: 1rem;">
                        <a href="/orders/<?= esc($order['order_number']) ?>" class="btn btn-primary">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= $this->include('templates/footer') ?>

<?= $this->include('templates/header') ?>

<h1>My Orders</h1>

<?php if (empty($orders)): ?>
    <p>You have no orders yet.</p>
    <a href="/products" class="btn btn-primary">Start Shopping</a>
<?php else: ?>
    <table style="width: 100%; border-collapse: collapse; margin-top: 2rem;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd;">
                <th style="padding: 1rem; text-align: left;">Order Number</th>
                <th style="padding: 1rem; text-align: left;">Date</th>
                <th style="padding: 1rem; text-align: left;">Status</th>
                <th style="padding: 1rem; text-align: left;">Total</th>
                <th style="padding: 1rem; text-align: left;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 1rem;">
                        <strong><?= esc($order['order_number']) ?></strong>
                    </td>
                    <td style="padding: 1rem;"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                    <td style="padding: 1rem;">
                        <span style="padding: 0.25rem 0.5rem; border-radius: 4px; background: #3498db; color: white;">
                            <?= esc(ucfirst($order['status'])) ?>
                        </span>
                    </td>
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

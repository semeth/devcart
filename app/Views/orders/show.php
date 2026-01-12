<?= $this->include('templates/header') ?>

<h1>Order #<?= esc($order['order_number']) ?></h1>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
    <div>
        <h2>Order Items</h2>
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd;">
                    <th style="padding: 1rem; text-align: left;">Product</th>
                    <th style="padding: 1rem; text-align: left;">Quantity</th>
                    <th style="padding: 1rem; text-align: left;">Price</th>
                    <th style="padding: 1rem; text-align: left;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 1rem;">
                            <strong><?= esc($item['product_name']) ?></strong>
                            <?php if ($item['product_sku']): ?>
                                <br><small>SKU: <?= esc($item['product_sku']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 1rem;"><?= $item['quantity'] ?></td>
                        <td style="padding: 1rem;">$<?= number_format($item['price'], 2) ?></td>
                        <td style="padding: 1rem;">$<?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($order['billing_address']): ?>
            <h2 style="margin-top: 2rem;">Billing Address</h2>
            <div style="background: #f9f9f9; padding: 1rem; border-radius: 4px; margin-top: 1rem;">
                <p><strong><?= esc($order['billing_address']['first_name'] . ' ' . $order['billing_address']['last_name']) ?></strong></p>
                <p><?= esc($order['billing_address']['address_line_1']) ?></p>
                <?php if ($order['billing_address']['address_line_2']): ?>
                    <p><?= esc($order['billing_address']['address_line_2']) ?></p>
                <?php endif; ?>
                <p><?= esc($order['billing_address']['city'] . ', ' . $order['billing_address']['state'] . ' ' . $order['billing_address']['postal_code']) ?></p>
                <p><?= esc($order['billing_address']['country']) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($order['shipping_address']): ?>
            <h2 style="margin-top: 2rem;">Shipping Address</h2>
            <div style="background: #f9f9f9; padding: 1rem; border-radius: 4px; margin-top: 1rem;">
                <p><strong><?= esc($order['shipping_address']['first_name'] . ' ' . $order['shipping_address']['last_name']) ?></strong></p>
                <p><?= esc($order['shipping_address']['address_line_1']) ?></p>
                <?php if ($order['shipping_address']['address_line_2']): ?>
                    <p><?= esc($order['shipping_address']['address_line_2']) ?></p>
                <?php endif; ?>
                <p><?= esc($order['shipping_address']['city'] . ', ' . $order['shipping_address']['state'] . ' ' . $order['shipping_address']['postal_code']) ?></p>
                <p><?= esc($order['shipping_address']['country']) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div>
        <h2>Order Summary</h2>
        <div style="border: 1px solid #ddd; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
            <div style="margin-bottom: 1rem;">
                <strong>Status:</strong><br>
                <span style="padding: 0.25rem 0.5rem; border-radius: 4px; background: #3498db; color: white; display: inline-block; margin-top: 0.5rem;">
                    <?= esc(ucfirst($order['status'])) ?>
                </span>
            </div>

            <div style="margin-bottom: 1rem;">
                <strong>Payment Status:</strong><br>
                <span style="padding: 0.25rem 0.5rem; border-radius: 4px; background: #27ae60; color: white; display: inline-block; margin-top: 0.5rem;">
                    <?= esc(ucfirst($order['payment_status'])) ?>
                </span>
            </div>

            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #ddd;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Subtotal:</span>
                    <span>$<?= number_format($order['subtotal'], 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Tax:</span>
                    <span>$<?= number_format($order['tax_amount'], 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Shipping:</span>
                    <span>$<?= number_format($order['shipping_amount'], 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem; margin-top: 1rem; padding-top: 1rem; border-top: 2px solid #ddd;">
                    <span>Total:</span>
                    <span>$<?= number_format($order['total_amount'], 2) ?></span>
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <p><strong>Order Date:</strong><br><?= date('F d, Y g:i A', strtotime($order['created_at'])) ?></p>
            </div>
        </div>
    </div>
</div>

<a href="/orders" class="btn mt-2">Back to Orders</a>

<?= $this->include('templates/footer') ?>

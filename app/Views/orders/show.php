<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <h1 class="mb-4">Order #<?= esc($order['order_number']) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($item['product_name']) ?></strong>
                                            <?php if ($item['product_sku']): ?>
                                                <br><small class="text-muted">SKU: <?= esc($item['product_sku']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>$<?= number_format($item['price'], 2) ?></td>
                                        <td>$<?= number_format($item['subtotal'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php if (!empty($order['billing_address'])): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Billing Address</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong><?= esc(($order['billing_address']['first_name'] ?? '') . ' ' . ($order['billing_address']['last_name'] ?? '')) ?></strong></p>
                        <p class="mb-1"><?= esc($order['billing_address']['address_line_1'] ?? '') ?></p>
                        <?php if (!empty($order['billing_address']['address_line_2'])): ?>
                            <p class="mb-1"><?= esc($order['billing_address']['address_line_2']) ?></p>
                        <?php endif; ?>
                        <p class="mb-1"><?= esc(($order['billing_address']['city'] ?? '') . ', ' . ($order['billing_address']['state'] ?? '') . ' ' . ($order['billing_address']['postal_code'] ?? '')) ?></p>
                        <p class="mb-0"><?= esc($order['billing_address']['country'] ?? '') ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($order['shipping_address'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong><?= esc(($order['shipping_address']['first_name'] ?? '') . ' ' . ($order['shipping_address']['last_name'] ?? '')) ?></strong></p>
                        <p class="mb-1"><?= esc($order['shipping_address']['address_line_1'] ?? '') ?></p>
                        <?php if (!empty($order['shipping_address']['address_line_2'])): ?>
                            <p class="mb-1"><?= esc($order['shipping_address']['address_line_2']) ?></p>
                        <?php endif; ?>
                        <p class="mb-1"><?= esc(($order['shipping_address']['city'] ?? '') . ', ' . ($order['shipping_address']['state'] ?? '') . ' ' . ($order['shipping_address']['postal_code'] ?? '')) ?></p>
                        <p class="mb-0"><?= esc($order['shipping_address']['country'] ?? '') ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge bg-primary mt-1"><?= esc(ucfirst($order['status'])) ?></span>
                    </div>

                    <div class="mb-3">
                        <strong>Payment Status:</strong><br>
                        <span class="badge bg-success mt-1"><?= esc(ucfirst($order['payment_status'])) ?></span>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>$<?= number_format($order['subtotal'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>$<?= number_format($order['tax_amount'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>$<?= number_format($order['shipping_amount'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5 mt-3 pt-3 border-top">
                            <span>Total:</span>
                            <span>$<?= number_format($order['total_amount'], 2) ?></span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="mb-1"><strong>Order Date:</strong></p>
                        <p class="text-muted"><?= date('F d, Y g:i A', strtotime($order['created_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="<?= site_url('orders') ?>" class="btn btn-secondary mt-4">← Back to Orders</a>
</div>

<?= $this->include('templates/footer') ?>

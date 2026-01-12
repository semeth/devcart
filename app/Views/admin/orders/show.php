<?= $this->include('admin/templates/header') ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Order #<?= esc($order['order_number']) ?></h1>
    </div>

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
                                    <th>SKU</th>
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
                                        </td>
                                        <td><?= esc($item['product_sku'] ?? 'N/A') ?></td>
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
                        <?php if (!empty($order['billing_address']['phone'])): ?>
                            <p class="mb-0 mt-2"><strong>Phone:</strong> <?= esc($order['billing_address']['phone']) ?></p>
                        <?php endif; ?>
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
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <?php if ($user): ?>
                        <div class="mb-3">
                            <strong>Customer:</strong><br>
                            <?= esc($user['first_name'] . ' ' . $user['last_name']) ?><br>
                            <small class="text-muted"><?= esc($user['email']) ?></small>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <strong>Customer:</strong><br>
                            <small class="text-muted">Guest Order</small><br>
                            <small><?= esc($order['email']) ?></small>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <strong>Order Date:</strong><br>
                        <?= date('F d, Y g:i A', strtotime($order['created_at'])) ?>
                    </div>

                    <?php if ($order['shipped_at']): ?>
                        <div class="mb-3">
                            <strong>Shipped At:</strong><br>
                            <?= date('F d, Y g:i A', strtotime($order['shipped_at'])) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($order['delivered_at']): ?>
                        <div class="mb-3">
                            <strong>Delivered At:</strong><br>
                            <?= date('F d, Y g:i A', strtotime($order['delivered_at'])) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($order['payment_method']): ?>
                        <div class="mb-3">
                            <strong>Payment Method:</strong><br>
                            <?= esc(ucfirst($order['payment_method'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Update Order</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= site_url('admin/orders/' . $order['order_number'] . '/update') ?>">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                <option value="refunded" <?= $order['status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-select" id="payment_status" name="payment_status">
                                <option value="pending" <?= $order['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="paid" <?= $order['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                <option value="failed" <?= $order['payment_status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                                <option value="refunded" <?= $order['payment_status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= esc($order['notes'] ?? '') ?></textarea>
                            <small class="form-text text-muted">Internal notes about this order</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Order</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge bg-primary"><?= esc(ucfirst($order['status'])) ?></span>
                    </div>

                    <div class="mb-3">
                        <strong>Payment Status:</strong><br>
                        <span class="badge bg-success"><?= esc(ucfirst($order['payment_status'])) ?></span>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>$<?= number_format($order['subtotal'], 2) ?></span>
                        </div>
                        <?php if ($order['discount_amount'] > 0): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span class="text-danger">-$<?= number_format($order['discount_amount'], 2) ?></span>
                            </div>
                        <?php endif; ?>
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
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/templates/footer') ?>

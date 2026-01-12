<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <h1 class="mb-4">Shopping Cart</h1>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">
            <p class="mb-3">Your cart is empty.</p>
            <a href="<?= site_url('products') ?>" class="btn btn-primary">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($item['name'] ?? 'Product') ?></strong>
                                        <?php if (!empty($item['sku'])): ?>
                                            <br><small class="text-muted">SKU: <?= esc($item['sku']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>$<?= number_format($item['price'], 2) ?></td>
                                    <td>
                                        <form method="post" action="<?= site_url('cart/update') ?>" class="d-inline-flex align-items-center gap-2">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="cart_item_id" value="<?= $item['id'] ?>">
                                            <input type="number" class="form-control form-control-sm" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width: 80px;">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                        </form>
                                    </td>
                                    <td><strong>$<?= number_format($item['price'] * $item['quantity'], 2) ?></strong></td>
                                    <td>
                                        <a href="<?= site_url('cart/remove/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remove this item?')">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong class="fs-5">$<?= number_format($cartTotal, 2) ?></strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 text-end">
            <a href="<?= site_url('products') ?>" class="btn btn-outline-secondary">Continue Shopping</a>
            <a href="<?= site_url('checkout') ?>" class="btn btn-success ms-2">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?= $this->include('templates/footer') ?>

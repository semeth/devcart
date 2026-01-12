<?= $this->include('templates/header') ?>

<h1>Shopping Cart</h1>

<?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
    <a href="/products" class="btn btn-primary">Continue Shopping</a>
<?php else: ?>
    <table style="width: 100%; border-collapse: collapse; margin-top: 2rem;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd;">
                <th style="padding: 1rem; text-align: left;">Product</th>
                <th style="padding: 1rem; text-align: left;">Price</th>
                <th style="padding: 1rem; text-align: left;">Quantity</th>
                <th style="padding: 1rem; text-align: left;">Subtotal</th>
                <th style="padding: 1rem; text-align: left;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 1rem;">
                        <strong><?= esc($item['name']) ?></strong>
                        <?php if ($item['sku']): ?>
                            <br><small>SKU: <?= esc($item['sku']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 1rem;">$<?= number_format($item['price'], 2) ?></td>
                    <td style="padding: 1rem;">
                        <form method="post" action="/cart/update" style="display: inline-block;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="cart_item_id" value="<?= $item['id'] ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width: 80px; padding: 0.25rem;">
                            <button type="submit" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.9rem;">Update</button>
                        </form>
                    </td>
                    <td style="padding: 1rem;">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    <td style="padding: 1rem;">
                        <a href="/cart/remove/<?= $item['id'] ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.9rem;" onclick="return confirm('Remove this item?')">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="padding: 1rem; text-align: right;"><strong>Total:</strong></td>
                <td style="padding: 1rem;"><strong>$<?= number_format($cartTotal, 2) ?></strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 2rem; text-align: right;">
        <a href="/products" class="btn">Continue Shopping</a>
        <a href="/checkout" class="btn btn-success" style="margin-left: 1rem;">Proceed to Checkout</a>
    </div>
<?php endif; ?>

<?= $this->include('templates/footer') ?>

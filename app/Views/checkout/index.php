<?= $this->include('templates/header') ?>

<h1>Checkout</h1>

<form method="post" action="/checkout/process" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
    <?= csrf_field() ?>
    <div>
        <h2>Billing Information</h2>
        
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" value="<?= esc($user['email'] ?? old('email')) ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" name="first_name" id="first_name" value="<?= esc($user['first_name'] ?? old('first_name')) ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" name="last_name" id="last_name" value="<?= esc($user['last_name'] ?? old('last_name')) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="address_line_1">Address Line 1 *</label>
            <input type="text" name="address_line_1" id="address_line_1" value="<?= esc($billingAddress['address_line_1'] ?? old('address_line_1')) ?>" required>
        </div>

        <div class="form-group">
            <label for="address_line_2">Address Line 2</label>
            <input type="text" name="address_line_2" id="address_line_2" value="<?= esc($billingAddress['address_line_2'] ?? old('address_line_2')) ?>">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="city">City *</label>
                <input type="text" name="city" id="city" value="<?= esc($billingAddress['city'] ?? old('city')) ?>" required>
            </div>
            <div class="form-group">
                <label for="state">State *</label>
                <input type="text" name="state" id="state" value="<?= esc($billingAddress['state'] ?? old('state')) ?>" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="postal_code">Postal Code *</label>
                <input type="text" name="postal_code" id="postal_code" value="<?= esc($billingAddress['postal_code'] ?? old('postal_code')) ?>" required>
            </div>
            <div class="form-group">
                <label for="country">Country *</label>
                <input type="text" name="country" id="country" value="<?= esc($billingAddress['country'] ?? old('country') ?? 'United States') ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" name="phone" id="phone" value="<?= esc($billingAddress['phone'] ?? old('phone')) ?>">
        </div>

        <?php if (session()->has('user_id')): ?>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="save_billing" value="1"> Save as default billing address
                </label>
            </div>
        <?php endif; ?>

        <h2 style="margin-top: 2rem;">Shipping Information</h2>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="same_as_billing" value="1" checked onchange="toggleShipping(this)"> Same as billing address
            </label>
        </div>

        <div id="shipping-fields" style="display: none;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="shipping_first_name">First Name</label>
                    <input type="text" name="shipping_first_name" id="shipping_first_name">
                </div>
                <div class="form-group">
                    <label for="shipping_last_name">Last Name</label>
                    <input type="text" name="shipping_last_name" id="shipping_last_name">
                </div>
            </div>
            <div class="form-group">
                <label for="shipping_address_line_1">Address Line 1</label>
                <input type="text" name="shipping_address_line_1" id="shipping_address_line_1">
            </div>
            <div class="form-group">
                <label for="shipping_city">City</label>
                <input type="text" name="shipping_city" id="shipping_city">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="shipping_state">State</label>
                    <input type="text" name="shipping_state" id="shipping_state">
                </div>
                <div class="form-group">
                    <label for="shipping_postal_code">Postal Code</label>
                    <input type="text" name="shipping_postal_code" id="shipping_postal_code">
                </div>
            </div>
            <div class="form-group">
                <label for="shipping_country">Country</label>
                <input type="text" name="shipping_country" id="shipping_country">
            </div>
        </div>
    </div>

    <div>
        <h2>Order Summary</h2>
        <div style="border: 1px solid #ddd; padding: 1rem; border-radius: 8px;">
            <?php foreach ($cartItems as $item): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;">
                    <div>
                        <strong><?= esc($item['name']) ?></strong>
                        <br><small>Qty: <?= $item['quantity'] ?></small>
                    </div>
                    <div>$<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                </div>
            <?php endforeach; ?>

            <div style="margin-top: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Subtotal:</span>
                    <span>$<?= number_format($totals['subtotal'], 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Tax:</span>
                    <span>$<?= number_format($totals['tax_amount'], 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Shipping:</span>
                    <span>$<?= number_format($totals['shipping_amount'], 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem; margin-top: 1rem; padding-top: 1rem; border-top: 2px solid #ddd;">
                    <span>Total:</span>
                    <span>$<?= number_format($totals['total_amount'], 2) ?></span>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 1rem; padding: 1rem; font-size: 1.2rem;">Place Order</button>
    </div>
</form>

<script>
function toggleShipping(checkbox) {
    document.getElementById('shipping-fields').style.display = checkbox.checked ? 'none' : 'block';
}
</script>

<?= $this->include('templates/footer') ?>

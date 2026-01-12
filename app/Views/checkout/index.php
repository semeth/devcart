<?= $this->include('templates/header') ?>

<div class="container mt-4">
    <h1 class="mb-4">Checkout</h1>

    <form method="post" action="<?= site_url('checkout/process') ?>">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Billing Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email'] ?? old('email')) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= esc($user['first_name'] ?? old('first_name')) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= esc($user['last_name'] ?? old('last_name')) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address_line_1" class="form-label">Address Line 1 *</label>
                            <input type="text" class="form-control" id="address_line_1" name="address_line_1" value="<?= esc($billingAddress['address_line_1'] ?? old('address_line_1')) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="address_line_2" class="form-label">Address Line 2</label>
                            <input type="text" class="form-control" id="address_line_2" name="address_line_2" value="<?= esc($billingAddress['address_line_2'] ?? old('address_line_2')) ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?= esc($billingAddress['city'] ?? old('city')) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">State *</label>
                                <input type="text" class="form-control" id="state" name="state" value="<?= esc($billingAddress['state'] ?? old('state')) ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Postal Code *</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?= esc($billingAddress['postal_code'] ?? old('postal_code')) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country *</label>
                                <input type="text" class="form-control" id="country" name="country" value="<?= esc($billingAddress['country'] ?? old('country') ?? 'United States') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= esc($billingAddress['phone'] ?? old('phone')) ?>">
                        </div>

                        <?php if (session()->has('user_id')): ?>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="save_billing" name="save_billing" value="1">
                                <label class="form-check-label" for="save_billing">Save as default billing address</label>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="same_as_billing" name="same_as_billing" value="1" checked onchange="toggleShipping(this)">
                            <label class="form-check-label" for="same_as_billing">Same as billing address</label>
                        </div>

                        <div id="shipping-fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="shipping_first_name" name="shipping_first_name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="shipping_last_name" name="shipping_last_name">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_address_line_1" class="form-label">Address Line 1</label>
                                <input type="text" class="form-control" id="shipping_address_line_1" name="shipping_address_line_1">
                            </div>
                            <div class="mb-3">
                                <label for="shipping_city" class="form-label">City</label>
                                <input type="text" class="form-control" id="shipping_city" name="shipping_city">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="shipping_state" name="shipping_state">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" id="shipping_postal_code" name="shipping_postal_code">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="shipping_country" name="shipping_country">
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($shippingOptions)): ?>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Shipping Method</h5>
                        </div>
                        <div class="card-body">
                            <?php $firstShipping = true; foreach ($shippingOptions as $option): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input shipping-option" type="radio" name="shipping_method" id="shipping_<?= esc($option['extension_code'] . '_' . $option['code']) ?>" value="<?= esc($option['extension_code'] . ':' . $option['code']) ?>" data-cost="<?= esc($option['cost']) ?>" <?= $firstShipping ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="shipping_<?= esc($option['extension_code'] . '_' . $option['code']) ?>">
                                        <strong><?= esc($option['name']) ?></strong>
                                        <span class="text-muted"> - $<?= number_format($option['cost'], 2) ?></span>
                                        <?php if (!empty($option['estimated_days'])): ?>
                                            <br><small class="text-muted">Estimated delivery: <?= $option['estimated_days'] ?> days</small>
                                        <?php endif; ?>
                                        <?php if (!empty($option['description'])): ?>
                                            <br><small class="text-muted"><?= esc($option['description']) ?></small>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php $firstShipping = false; endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($paymentOptions)): ?>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <?php $firstPayment = true; foreach ($paymentOptions as $option): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_<?= esc($option['code']) ?>" value="<?= esc($option['code']) ?>" <?= $firstPayment ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="payment_<?= esc($option['code']) ?>">
                                        <strong><?= esc($option['name']) ?></strong>
                                        <?php if (!empty($option['description'])): ?>
                                            <br><small class="text-muted"><?= esc($option['description']) ?></small>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php $firstPayment = false; endforeach; ?>
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
                        <?php foreach ($cartItems as $item): ?>
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <div>
                                    <strong><?= esc($item['name']) ?></strong>
                                    <br><small class="text-muted">Qty: <?= $item['quantity'] ?></small>
                                </div>
                                <div>$<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                            </div>
                        <?php endforeach; ?>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>$<?= number_format($totals['subtotal'], 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>$<?= number_format($totals['tax_amount'], 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span id="shipping-amount">$<?= number_format($totals['shipping_amount'], 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 mt-3 pt-3 border-top">
                                <span>Total:</span>
                                <span id="total-amount">$<?= number_format($totals['total_amount'], 2) ?></span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-4 btn-lg">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shippingOptions = document.querySelectorAll('.shipping-option');
    const shippingAmountElement = document.getElementById('shipping-amount');
    const totalAmountElement = document.getElementById('total-amount');
    const subtotal = <?= $subtotal ?>;

    function updateTotals() {
        const selectedShipping = document.querySelector('.shipping-option:checked');
        const shippingCost = selectedShipping ? parseFloat(selectedShipping.getAttribute('data-cost')) : 0;
        const tax = 0; // Tax calculation can be added later
        const total = subtotal + shippingCost + tax;

        if (shippingAmountElement) {
            shippingAmountElement.textContent = '$' + shippingCost.toFixed(2);
        }
        if (totalAmountElement) {
            totalAmountElement.textContent = '$' + total.toFixed(2);
        }
    }

    shippingOptions.forEach(function(option) {
        option.addEventListener('change', updateTotals);
    });

    // Update totals on page load
    updateTotals();
});
</script>

<?= $this->include('templates/footer') ?>

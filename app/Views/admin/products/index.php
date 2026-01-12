<?= $this->include('templates/header') ?>

<h1>Manage Products</h1>

<a href="/admin" class="btn">Back to Dashboard</a>

<?php if (empty($products)): ?>
    <p>No products found.</p>
<?php else: ?>
    <table style="width: 100%; border-collapse: collapse; margin-top: 2rem;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd;">
                <th style="padding: 1rem; text-align: left;">Name</th>
                <th style="padding: 1rem; text-align: left;">SKU</th>
                <th style="padding: 1rem; text-align: left;">Price</th>
                <th style="padding: 1rem; text-align: left;">Stock</th>
                <th style="padding: 1rem; text-align: left;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 1rem;"><?= esc($product['name']) ?></td>
                    <td style="padding: 1rem;"><?= esc($product['sku']) ?></td>
                    <td style="padding: 1rem;">$<?= number_format($product['price'], 2) ?></td>
                    <td style="padding: 1rem;"><?= $product['stock_quantity'] ?></td>
                    <td style="padding: 1rem;">
                        <?php if ($product['is_active']): ?>
                            <span style="color: #27ae60;">Active</span>
                        <?php else: ?>
                            <span style="color: #e74c3c;">Inactive</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= $this->include('templates/footer') ?>

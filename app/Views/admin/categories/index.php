<?= $this->include('templates/header') ?>

<h1>Manage Categories</h1>

<a href="/admin" class="btn">Back to Dashboard</a>

<?php if (empty($categories)): ?>
    <p>No categories found.</p>
<?php else: ?>
    <table style="width: 100%; border-collapse: collapse; margin-top: 2rem;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd;">
                <th style="padding: 1rem; text-align: left;">Name</th>
                <th style="padding: 1rem; text-align: left;">Slug</th>
                <th style="padding: 1rem; text-align: left;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 1rem;"><?= esc($category['name']) ?></td>
                    <td style="padding: 1rem;"><?= esc($category['slug']) ?></td>
                    <td style="padding: 1rem;">
                        <?php if ($category['is_active']): ?>
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

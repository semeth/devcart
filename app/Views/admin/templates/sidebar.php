<?php
// Get current route to highlight active menu item
$currentRoute = uri_string();
$isActive = function($path) use ($currentRoute) {
    return strpos($currentRoute, $path) === 0 ? 'active' : '';
};
?>

<nav id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h3>DevCart Admin</h3>
        <button class="btn btn-sm btn-outline-light d-md-none" id="sidebarToggle">
            <span>☰</span>
        </button>
    </div>
    
    <ul class="list-unstyled components">
        <!-- Dashboard -->
        <li>
            <a href="<?= site_url('admin') ?>" class="<?= $isActive('admin') && $currentRoute === 'admin' ? 'active' : '' ?>">
                <span class="icon">📊</span>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <!-- Catalog Section -->
        <li class="menu-section">
            <span class="section-title">Catalog</span>
        </li>
        <li>
            <a href="<?= site_url('admin/products') ?>" class="<?= $isActive('admin/products') ? 'active' : '' ?>">
                <span class="icon">📦</span>
                <span class="text">Products</span>
            </a>
        </li>
        <li>
            <a href="<?= site_url('admin/categories') ?>" class="<?= $isActive('admin/categories') ? 'active' : '' ?>">
                <span class="icon">📁</span>
                <span class="text">Categories</span>
            </a>
        </li>

        <!-- Sales Section -->
        <li class="menu-section">
            <span class="section-title">Sales</span>
        </li>
        <li>
            <a href="<?= site_url('admin/orders') ?>" class="<?= $isActive('admin/orders') ? 'active' : '' ?>">
                <span class="icon">🛒</span>
                <span class="text">Orders</span>
            </a>
        </li>

        <!-- Settings Section -->
        <li class="menu-section">
            <span class="section-title">Settings</span>
        </li>
        <li>
            <a href="<?= site_url('admin/extensions') ?>" class="<?= $isActive('admin/extensions') ? 'active' : '' ?>">
                <span class="icon">🔌</span>
                <span class="text">Extensions</span>
            </a>
        </li>

        <!-- Back to Store -->
        <li class="menu-section mt-4">
            <a href="<?= site_url('/') ?>" class="back-to-store">
                <span class="icon">🏠</span>
                <span class="text">Back to Store</span>
            </a>
        </li>
    </ul>
</nav>

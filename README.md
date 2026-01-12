# 🛒 DevCart - eCommerce Platform

A modern, feature-rich eCommerce platform built with **CodeIgniter 4**, designed as a modular and extensible foundation for online stores.

![Status](https://img.shields.io/badge/status-active%20development-yellow)
![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-orange)
![License](https://img.shields.io/badge/license-MIT-green)

---

## 📋 Table of Contents

- [Features](#-features)
- [Changelog](#-changelog)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### 🎯 Core Features (Implemented)

- **Product Management**
  - Full CRUD operations for products
  - Product categories with hierarchical support and full path display
  - Image uploads for products and categories
  - Stock management
  - Product variants (price, compare price, cost price)
  - SEO-friendly URLs (slugs)
  - Featured products
  - Products from subcategories display on parent category pages

- **Shopping Cart**
  - Guest and registered user cart support
  - Session-based cart for guests
  - User-specific cart for logged-in users
  - Real-time cart count updates
  - Quantity management

- **Checkout & Orders**
  - Complete checkout process
  - Billing and shipping address management
  - Shipping method selection (from active extensions)
  - Payment method selection (from active extensions)
  - Dynamic shipping cost calculation
  - Real-time order total updates
  - Order tracking
  - Order history for customers
  - Order management for admins

- **Extension System** ⭐ (New in 0.1.0)
  - **Generic extension architecture** for payment and shipping methods
  - **Payment Extensions**
    - Cash on Delivery (COD) - fully implemented
    - Extension system ready for Stripe, PayPal, and other payment gateways
  - **Shipping Extensions**
    - Flat Rate Shipping (ready for development)
    - Weight-Based Shipping (ready for development)
    - Free Shipping (ready for development)
  - **Admin Extension Management**
    - Enable/disable extensions
    - Configure extension settings with dynamic forms
    - Settings encryption for sensitive data (API keys, passwords)
    - Extension status management
  - **Checkout Integration**
    - Active extensions automatically appear in checkout
    - Shipping costs calculated dynamically
    - Extensions checked for availability based on order details

- **User Authentication**
  - User registration
  - Login/Logout
  - Password reset functionality
  - Role-based access control (Admin/Customer)
  - Session management

- **Admin Dashboard**
  - Comprehensive admin panel with sidebar navigation
  - Product management interface
  - Category management with hierarchy display
  - Order management
  - Extension management (new)
  - Dashboard statistics
  - User management
  - Categorized menu with icons
  - Responsive sidebar navigation

- **Frontend**
  - Responsive Bootstrap 5 design
  - Modern, clean UI/UX
  - Product catalog with search
  - Category browsing with hierarchical navigation
  - Product detail pages
  - Shopping cart interface
  - Sticky footer layout

### 🚀 Planned Features

- Payment gateway integration (Stripe, PayPal) - Can be added as extensions
- Email notifications
- Tax calculation system
- Full order processing integration with extensions
- Product reviews and ratings
- Wishlist functionality
- Coupon/Discount system
- Multi-currency support
- Advanced reporting and analytics
- Inventory alerts
- Product image gallery (multiple images per product)
- Advanced search and filtering

---

## 📝 Changelog

### Version 0.1.0 (Current) - Extension System Release
- ✅ **Extension System Implementation**
  - Created generic extension system for payment and shipping methods
  - Implemented ExtensionManager service for dynamic extension loading
  - Created base interfaces and abstract classes for extensions
  - Added database tables for extensions and extension settings
  - Implemented settings encryption for sensitive data
- ✅ **Payment Extensions**
  - Implemented Cash on Delivery (COD) payment method
  - Created payment extension interface and base implementation
  - Admin interface for payment extension configuration
- ✅ **Shipping Extensions**
  - Created Flat Rate, Weight-Based, and Free Shipping extensions (dummy implementations ready for development)
  - Implemented shipping extension interface with cost calculation
  - Admin interface for shipping extension configuration
- ✅ **Checkout Integration**
  - Integrated active extensions into checkout process
  - Shipping methods now display based on extension active state
  - Payment methods now display based on extension active state
  - Dynamic shipping cost calculation and display
  - JavaScript updates for real-time total calculation
- ✅ **Admin Panel Improvements**
  - Created new sidebar navigation with categorized menu items
  - Added icons to admin menu for better identification
  - Improved admin layout with responsive sidebar
  - Added Extensions management section in admin panel
  - Extension configuration interface with dynamic form generation
  - Extension enable/disable functionality
- ✅ **Category Management Enhancements**
  - Categories now display with full hierarchy path (e.g., "Electronics >> Phones")
  - Categories sorted by hierarchy path for better organization
  - Added `getCategoryPath()` method to CategoryModel for recursive hierarchy building
- ✅ **Database Schema Updates**
  - Added `extensions` table for extension management
  - Added `extension_settings` table for extension configuration
  - Added `shipping_method` field to orders table

### Version 0.0.10
- ✅ Fixed product CRUD operations (add/update) to match category functionality
- ✅ Fixed product image upload and database update synchronization
- ✅ Updated SKU validation to allow colons, dashes, and underscores (`regex_match[/^[a-zA-Z0-9\-_:]+$/]`)
- ✅ Implemented recursive category traversal - products from subcategories now display on parent category pages
- ✅ Fixed route ordering for product save operations (prioritized specific route over general)
- ✅ Improved validation error display in admin product forms
- ✅ Cleaned up debug logging and temporary files
- ✅ Aligned product save logic with working category save implementation
- ✅ Added `getAllDescendantIds()` method to CategoryModel for recursive category fetching
- ✅ Added `getByCategories()` method to ProductModel for multi-category product queries

### Version 0.0.6
- ✅ Fixed image upload database persistence issue
- ✅ Fixed image URL display across all frontend views
- ✅ All images now use `base_url()` for proper URL generation
- ✅ Fixed subcategory image display on category pages
- ✅ Added fallback "No Image" placeholders for better UX
- ✅ Improved error handling in image upload process
- ✅ Enhanced product and category save methods with better validation

### Version 0.0.5
- ✅ Centralized CSS and JS to assets directory
- ✅ Removed inline styles and scripts from views
- ✅ Implemented image upload system for products and categories
- ✅ Created organized upload directory structure (`public/uploads/products/`, `public/uploads/categories/`)
- ✅ Added image preview in admin forms
- ✅ Implemented automatic image cleanup on delete
- ✅ Added file validation (max 2MB, image types only)

### Version 0.0.4
- ✅ Adapted project to Bootstrap 5 and jQuery
- ✅ Implemented basic admin and front functionalities
- ✅ User registration, login, and logout
- ✅ Shopping cart functionality
- ✅ Checkout form and order placement
- ✅ Admin features for category and product management
- ✅ Dashboard statistics
- ✅ Various eCommerce basic functionalities

### Version 0.0.1
- 🎉 Initial project setup

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Contribution Guidelines

- Follow PSR-12 coding standards
- Write clear commit messages
- Add comments for complex logic
- Update documentation as needed
- Test your changes thoroughly

---

## 📄 License

This project uses the same license as CodeIgniter 4 (MIT License). See the [LICENSE](LICENSE) file for details.

---

## 👥 Authors

- **Andrei Dumitru** - *Initial work* - [semeth](https://github.com/semeth)

---

## 🙏 Acknowledgments

- CodeIgniter 4 framework
- Bootstrap team for the excellent CSS framework
- jQuery team
- All contributors and users of this project

---

## 📞 Support

For support, email **thesemeth@gmail.com**. 

**Response Time**: We aim to respond within 24-48 hours.

**Note**: Additional support channels (GitHub Issues, Discord, etc.) will be added when the project launches.

---

## ⚠️ Disclaimer

This project is currently in **active development** and is **not production-ready**. 

While you are free to use this project, please be aware that:
- It is still under active development
- Features may change or be incomplete
- It is recommended for development and testing purposes only
- Use at your own risk

A production-ready release and live demo are expected with version 0.8.0.

---

**Made with ❤️ using CodeIgniter 4**

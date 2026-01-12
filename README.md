# 🛒 DevCart - eCommerce Platform

A modern, feature-rich eCommerce platform built with **CodeIgniter 4**, designed as a modular and extensible foundation for online stores.

![Status](https://img.shields.io/badge/status-active%20development-yellow)
![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-orange)
![License](https://img.shields.io/badge/license-MIT-green)

---

## 📋 Table of Contents

- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Setup](#-database-setup)
- [Usage](#-usage)
- [Project Structure](#-project-structure)
- [Tech Stack](#-tech-stack)
- [Development](#-development)
- [Changelog](#-changelog)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### 🎯 Core Features (Implemented)

- **Product Management**
  - Full CRUD operations for products
  - Product categories with hierarchical support
  - Image uploads for products and categories
  - Stock management
  - Product variants (price, compare price, cost price)
  - SEO-friendly URLs (slugs)
  - Featured products

- **Shopping Cart**
  - Guest and registered user cart support
  - Session-based cart for guests
  - User-specific cart for logged-in users
  - Real-time cart count updates
  - Quantity management

- **Checkout & Orders**
  - Complete checkout process
  - Billing and shipping address management
  - Order tracking
  - Order history for customers
  - Order management for admins

- **User Authentication**
  - User registration
  - Login/Logout
  - Password reset functionality
  - Role-based access control (Admin/Customer)
  - Session management

- **Admin Dashboard**
  - Comprehensive admin panel
  - Product management interface
  - Category management
  - Order management
  - Dashboard statistics
  - User management

- **Frontend**
  - Responsive Bootstrap 5 design
  - Modern, clean UI/UX
  - Product catalog with search
  - Category browsing
  - Product detail pages
  - Shopping cart interface

### 🚀 Planned Features

- Payment gateway integration (Stripe, PayPal)
- Email notifications
- Advanced shipping options
- Tax calculation system
- Product reviews and ratings
- Wishlist functionality
- Coupon/Discount system
- Multi-currency support
- Advanced reporting and analytics
- Inventory alerts
- Product image gallery
- Advanced search and filtering

---

## 📦 Requirements

- **PHP**: 8.0 or higher
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Web Server**: Apache (with mod_rewrite) or Nginx
- **Extensions**: 
  - `intl`
  - `mbstring`
  - `openssl`
  - `pdo`
  - `pdo_mysql`
  - `gd` (for image processing)

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/devcart.git
cd devcart
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

Copy the environment file:

```bash
cp env .env
```

### 4. Configure Database

Edit `.env` file and set your database credentials:

```env
database.default.hostname = localhost
database.default.database = devcart_db
database.default.username = your_username
database.default.password = your_password
database.default.DBDriver = MySQLi
```

### 5. Set Base URL

Update the base URL in `.env`:

```env
app.baseURL = 'http://localhost:8080/'
```

Or in `app/Config/App.php`:

```php
public string $baseURL = 'http://localhost:8080/';
```

### 6. Set Encryption Key

Generate a new encryption key:

```bash
php spark key:generate
```

Or manually set in `.env`:

```env
encryption.key = your-32-character-key-here
```

### 7. Set Writable Permissions

```bash
# Linux/Mac
chmod -R 755 writable/
chmod -R 755 public/uploads/

# Windows (if needed)
icacls writable /grant Users:F /T
icacls public\uploads /grant Users:F /T
```

---

## ⚙️ Configuration

### Database Configuration

1. Create a new MySQL database:

```sql
CREATE DATABASE devcart_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Run migrations:

```bash
php spark migrate
```

This will create all necessary tables:
- `users`
- `categories`
- `products`
- `cart_items`
- `orders`
- `order_items`
- `addresses`
- `payments`

### Create Admin User

After running migrations, create an admin user manually in the database or through registration and update the role:

```sql
UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
```

---

## 📖 Usage

### Accessing the Application

- **Frontend**: `http://localhost:8080/`
- **Admin Panel**: `http://localhost:8080/admin` (requires admin login)

### Default Admin Access

1. Register a new account
2. Update the user role in the database to `admin`
3. Log in with admin credentials

### Managing Products

1. Navigate to **Admin → Products**
2. Click **Add New Product**
3. Fill in product details
4. Upload product image
5. Set pricing and inventory
6. Save product

### Managing Categories

1. Navigate to **Admin → Categories**
2. Click **Add Category**
3. Set category name, slug, and description
4. Optionally set a parent category for hierarchical structure
5. Upload category image
6. Save category

### Shopping Flow

1. Browse products on the homepage or category pages
2. Click on a product to view details
3. Add products to cart
4. Proceed to checkout
5. Fill in billing and shipping information
6. Place order
7. View order history in "My Orders"

---

## 📁 Project Structure

```
devcart/
├── app/
│   ├── Config/          # Configuration files
│   ├── Controllers/      # Application controllers
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── CategoryController.php
│   │   ├── CheckoutController.php
│   │   ├── Home.php
│   │   ├── OrderController.php
│   │   └── ProductController.php
│   ├── Database/
│   │   └── Migrations/   # Database migrations
│   ├── Filters/          # Request filters
│   ├── Models/           # Data models
│   │   ├── UserModel.php
│   │   ├── ProductModel.php
│   │   ├── CategoryModel.php
│   │   ├── CartItemModel.php
│   │   ├── OrderModel.php
│   │   └── ...
│   └── Views/            # View templates
│       ├── templates/    # Header, footer
│       ├── admin/        # Admin views
│       ├── auth/         # Authentication views
│       ├── products/     # Product views
│       └── ...
├── public/
│   ├── assets/           # Static assets
│   │   ├── bootstrap/   # Bootstrap CSS/JS
│   │   ├── css/         # Custom CSS
│   │   ├── js/          # Custom JavaScript
│   │   └── jquery/      # jQuery library
│   ├── uploads/         # Uploaded files
│   │   ├── products/    # Product images
│   │   └── categories/  # Category images
│   └── index.php        # Entry point
├── writable/            # Writable directories
│   ├── cache/           # Cache files
│   ├── logs/            # Log files
│   └── session/         # Session files
└── .env                 # Environment configuration
```

---

## 🛠️ Tech Stack

### Backend
- **Framework**: CodeIgniter 4
- **Language**: PHP 8.0+
- **Database**: MySQL/MariaDB
- **ORM**: CodeIgniter's built-in Query Builder

### Frontend
- **CSS Framework**: Bootstrap 5
- **JavaScript**: Vanilla JS + jQuery 3.7.1
- **Icons**: Bootstrap Icons (via Bootstrap)

### Development Tools
- **Composer**: Dependency management
- **CodeIgniter CLI**: `php spark` commands
- **Git**: Version control

---

## 🔧 Development

### Running the Development Server

```bash
php spark serve
```

The application will be available at `http://localhost:8080`

### Database Migrations

```bash
# Run migrations
php spark migrate

# Rollback last migration
php spark migrate:rollback

# Create new migration
php spark make:migration MigrationName
```

### Code Style

Follow PSR-12 coding standards and CodeIgniter 4 conventions.

### Debugging

Enable debug mode in `.env`:

```env
CI_ENVIRONMENT = development
```

Logs are available in `writable/logs/`

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

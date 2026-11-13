# SAVAGEXO CLOTHINGS — Luxury Contemporary Fashion & Global Sneaker Archive

[![SAVAGEXO Brand](https://img.shields.io/badge/Brand-SAVAGEXO%20CLOTHINGS-black?style=for-the-badge)](https://github.com/SAVAGEXO45)
[![Framework](https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Styling](https://img.shields.io/badge/Tailwind_CSS-v4-38bdf8?style=for-the-badge&logo=tailwindcss)](https://tailwindcss.com)
[![Support](https://img.shields.io/badge/Support-SAVAGEXO%40GMAIL.COM-blue?style=for-the-badge)](mailto:SAVAGEXO@GMAIL.COM)

**SAVAGEXO CLOTHINGS** is a full-stack luxury fashion and global sneaker archive e-commerce platform built with **Laravel**, **Blade Templates**, **Tailwind CSS**, **Alpine.js**, and **MySQL / SQLite**.

---

## 1. Project Architecture & Features

- **Dedicated Sneakers Destination (`/sneakers`)**:
  - Curated collection featuring 16+ iconic global footwear brands: **Nike, Adidas, Puma, New Balance, Jordan, Asics, Converse, Vans, Reebok, Salomon, On Running**, and more.
  - Category filters: *Running, Basketball, Lifestyle, Casual, Retro, High-top, Low-top, Training, Luxury, Streetwear*.
  - Multi-angle studio product photography with interactive gallery zoom, size selectors (EU 38–46), color swatch variations, stock counts, and prices in Nigerian Naira (₦).
- **Luxury Ready-to-Wear Fashion (`/shop`)**:
  - In-house couture, Agbada sets, tailored outerwear, double-breasted virgin wool coats, gabardine trousers, asymmetric silk dresses, leather boots, and artisan leather goods.
- **Store Operations & Management**:
  - Official store contact email updated to: `SAVAGEXO@GMAIL.COM`.
  - Full guest and user cart persistence with instant drawer and checkout.
  - Multi-gateway payment handling (Paystack, Flutterwave, Cash on Delivery).
  - Live order tracking (`/track-order`) with real-time timeline status.
  - Role-protected Atelier Executive Admin Dashboard (`/admin`) for products, variants, orders, banners, coupons, and customer management.

---

## 2. Technology Stack

- **Backend**: Laravel MVC Architecture
- **Frontend**: Blade Templates, Tailwind CSS v4, Alpine.js, Lucide Icons
- **Database**: SQLite (default zero-config) / MySQL 8.0+
- **Build Tool**: Vite

---

## 3. Installation & Quickstart

```bash
# 1. Clone the repository
git clone https://github.com/SAVAGEXO45/savagexo-clothings.git
cd savagexo-clothings

# 2. Install PHP and Node dependencies
composer install
npm install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Migrate and seed the database with catalog and sneakers
php artisan migrate:fresh --seed
php artisan db:seed --class=CatalogUpgradeSeeder

# 5. Build frontend assets
npm run build

# 6. Start the development server
php artisan serve --port=8088
```

---

## 4. Local Development Login Credentials

### Administrator Account
- **URL**: `http://localhost:8088/login` (redirects to `/admin`)
- **Email**: `admin@savagexo.com`
- **Password**: `SavageAdmin@2026`

### Sample Customer Accounts
- **Email**: `amara.okafor@gmail.com` | **Password**: `Customer@2026`
- **Email**: `damilola.balogun@gmail.com` | **Password**: `Customer@2026`

### Active Privilege / Discount Codes
- `SAVAGE15` — 15% off orders above ₦80,000
- `LAGOSVIP` — ₦25,000 off orders above ₦200,000
- `ROYALTY10` — 10% storewide discount (no minimum spend)

---

## 5. Contact & Inquiries
- **Official Brand Email**: [SAVAGEXO@GMAIL.COM](mailto:SAVAGEXO@GMAIL.COM)
- **Flagship Studio**: Victoria Island, Lagos, Nigeria

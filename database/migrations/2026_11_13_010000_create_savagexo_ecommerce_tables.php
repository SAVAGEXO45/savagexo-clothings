<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('gender_group', 30)->default('unisex')->index(); // men, women, unisex, accessories
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 60)->unique();
            $table->string('short_description', 500)->nullable();
            $table->longText('description');
            $table->decimal('price', 12, 2)->index();
            $table->decimal('discount_price', 12, 2)->nullable()->index();
            $table->integer('stock_quantity')->default(0)->index();
            $table->string('gender', 30)->default('unisex')->index(); // men, women, unisex
            $table->string('material')->nullable();
            $table->text('care_instructions')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_new_arrival')->default(false)->index();
            $table->boolean('is_best_seller')->default(false)->index();
            $table->boolean('is_trending')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('sales_count')->default(0)->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
        });

        // 3. Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 4. Product Variants (Sizes & Colors)
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('size', 30)->index(); // XS, S, M, L, XL, XXL, 40, 41, 42, 43, 44, One Size
            $table->string('color', 50)->index(); // Obsidian Black, Ivory, Terracotta, etc.
            $table->string('color_hex', 20)->default('#111111');
            $table->string('sku_variant', 80)->nullable();
            $table->integer('stock_quantity')->default(10);
            $table->decimal('price_adjustment', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Shopping Carts
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('session_id', 120)->nullable()->index();
            $table->string('coupon_code', 50)->nullable();
            $table->timestamps();
        });

        // 6. Cart Items
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('size', 30)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('color_hex', 20)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->timestamps();
        });

        // 7. Addresses
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('label', 50)->default('Home'); // Home, Office, Studio
            $table->string('recipient_name');
            $table->string('phone', 30);
            $table->string('street_address');
            $table->string('apartment')->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('postal_code', 30)->nullable();
            $table->string('country', 100)->default('Nigeria');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 8. Coupons
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('description')->nullable();
            $table->string('type', 20)->default('percentage'); // percentage, fixed
            $table->decimal('value', 12, 2);
            $table->decimal('min_order_amount', 12, 2)->default(0);
            $table->decimal('max_discount_amount', 12, 2)->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 9. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 40)->unique();
            $table->string('tracking_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email')->index();
            $table->string('customer_phone', 30);
            $table->string('shipping_address');
            $table->string('shipping_city', 100);
            $table->string('shipping_state', 100);
            $table->string('shipping_postal_code', 30)->nullable();
            $table->string('shipping_country', 100)->default('Nigeria');
            $table->string('shipping_method', 50)->default('standard'); // standard, express, lagos_same_day
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->string('coupon_code', 50)->nullable();
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('payment_method', 30)->default('paystack'); // paystack, flutterwave, cod
            $table->string('payment_status', 30)->default('pending')->index(); // pending, paid, failed, refunded
            $table->string('order_status', 30)->default('pending')->index(); // pending, confirmed, processing, shipped, out_for_delivery, delivered, cancelled
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        // 10. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name');
            $table->string('product_sku', 80)->nullable();
            $table->string('product_image')->nullable();
            $table->string('size', 30)->nullable();
            $table->string('color', 50)->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();
        });

        // 11. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('gateway', 30)->index(); // paystack, flutterwave, cod
            $table->string('transaction_reference', 100)->unique();
            $table->string('gateway_reference', 100)->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('NGN');
            $table->string('status', 30)->default('pending')->index(); // pending, success, failed, refunded
            $table->string('channel', 50)->nullable(); // card, bank_transfer, ussd, cash_on_delivery
            $table->json('gateway_response')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 12. Wishlists
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
        });

        // 13. Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('title')->nullable();
            $table->text('comment');
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_approved')->default(true)->index();
            $table->timestamps();
        });

        // 14. Homepage Banners
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('eyebrow', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('image_path');
            $table->string('cta_text', 60)->default('Explore Collection');
            $table->string('cta_link')->default('/shop');
            $table->string('secondary_cta_text', 60)->nullable();
            $table->string('secondary_cta_link')->nullable();
            $table->string('position', 40)->default('hero')->index(); // hero, promo_primary, promo_secondary, announcement
            $table->string('badge_text', 60)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 15. Store Settings (Configurable Shipping Fees, Gateway Toggles, Announcement Bar)
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general')->index();
            $table->string('type', 30)->default('text'); // text, number, boolean, json
            $table->timestamps();
        });

        // 16. Newsletter Subscribers
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('store_settings');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};

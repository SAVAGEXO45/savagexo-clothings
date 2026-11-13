<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Brands Table (Sneaker & Fashion Brands)
        if (! Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('type', 30)->default('sneaker')->index(); // sneaker, fashion, luxury
                $table->string('origin_country', 80)->nullable();
                $table->text('description')->nullable();
                $table->string('logo_path')->nullable();
                $table->string('banner_image')->nullable();
                $table->boolean('is_featured')->default(true)->index();
                $table->boolean('is_active')->default(true)->index();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 2. Add Brand & Sneaker Style columns to Products
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'brand_id')) {
                $table->foreignId('brand_id')->nullable()->after('category_id')->constrained('brands')->nullOnDelete();
            }
            if (! Schema::hasColumn('products', 'brand_name')) {
                $table->string('brand_name', 80)->nullable()->after('brand_id')->index();
            }
            if (! Schema::hasColumn('products', 'sneaker_style')) {
                $table->string('sneaker_style', 60)->nullable()->after('gender')->index();
                // Running, Basketball, Lifestyle, Casual, Retro, High-top, Low-top, Training, Luxury, Streetwear
            }
            if (! Schema::hasColumn('products', 'product_type')) {
                $table->string('product_type', 40)->default('clothing')->after('sneaker_style')->index(); // clothing, sneaker, accessory
            }
        });

        // 3. Add View Angle & Colorway Metadata to Product Images
        Schema::table('product_images', function (Blueprint $table) {
            if (! Schema::hasColumn('product_images', 'view_angle')) {
                $table->string('view_angle', 60)->nullable()->after('alt_text');
                // Front View, Back View, Side Profile, Fabric & Detail Close-Up, Editorial Lookbook, Colorway Variant
            }
            if (! Schema::hasColumn('product_images', 'color_name')) {
                $table->string('color_name', 60)->nullable()->after('view_angle')->index();
            }
        });

        // 4. Contact Inquiries Table for Contact Page
        if (! Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone', 40)->nullable();
                $table->string('subject');
                $table->text('message');
                $table->string('status', 30)->default('new');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn(['view_angle', 'color_name']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('brand_id');
            $table->dropColumn(['brand_name', 'sneaker_style', 'product_type']);
        });

        Schema::dropIfExists('brands');
    }
};

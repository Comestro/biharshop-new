<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            // SKU & slug
            $table->string('sku')->nullable()->unique()->after('name');
            $table->string('slug')->nullable()->unique()->after('sku');
            $table->string('brand')->nullable()->after('slug');

            // Category & type
            $table->foreignId('subcategory_id')->nullable()->constrained('categories')->onDelete('set null')->after('category_id');
            $table->string('product_type')->nullable()->after('subcategory_id'); // e.g., clothing, electronics, shoes
            $table->json('tags')->nullable()->after('product_type'); // multiple tags

            // Variants
            $table->string('color')->nullable()->after('brand');
            $table->string('size')->nullable()->after('color'); // for clothing/shoes
            $table->string('material')->nullable()->after('size');



            // Pricing
            $table->decimal('mrp', 10, 2)->nullable()->after('price');
            $table->decimal('discount', 5, 2)->nullable()->after('mrp'); // percentage
            $table->decimal('rating_avg', 3, 2)->default(0)->after('discount'); // average rating
            $table->integer('review_count')->default(0)->after('rating_avg');
            $table->string('currency', 10)->default('INR')->after('review_count');

            // Inventory
            $table->integer('quantity')->default(0)->after('currency');
            $table->string('in_stock')->nullable()->after('quantity');
            $table->integer('min_order_quantity')->default(1)->after('in_stock');

            // Shipping
            $table->decimal('weight', 10, 2)->nullable()->after('min_order_quantity');
            $table->decimal('length', 10, 2)->nullable()->after('weight');
            $table->decimal('width', 10, 2)->nullable()->after('length');
            $table->decimal('height', 10, 2)->nullable()->after('width');

            // Status & flags
            $table->boolean('is_active')->default(true)->after('height');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->boolean('is_new_arrival')->default(false)->after('is_featured');
            $table->boolean('is_on_sale')->default(false)->after('is_new_arrival');

            // SEO
            $table->string('meta_title')->nullable()->after('is_on_sale');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');

            // Warranty & Return
            $table->string('warranty_period')->nullable()->after('meta_keywords');
            $table->string('return_period')->nullable()->after('warranty_period');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'image_id',
                'image1',
                'image1_id',
                'image2',
                'image2_id',
                'image3',
                'image3_id',
                'sku',
                'slug',
                'brand',
                'subcategory_id',
                'product_type',
                'tags',
                'color',
                'size',
                'mrp',
                'discount',
                'rating_avg',
                'review_count',
                'currency',
                'quantity',
                'in_stock',
                'min_order_quantity',
                'weight',
                'length',
                'width',
                'height',
                'is_active',
                'is_featured',
                'is_new_arrival',
                'is_on_sale',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'warranty_period',
                'return_period'
            ]);
        });
    }
};

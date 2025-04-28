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
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('shop_category_id')->constrained('shop_categories')->cascadeOnDelete();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('unit')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->string('image')->nullable();
            $table->enum('source_type', ['local', 'global'])->default('local');
            $table->foreignId('global_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('slug');
            $table->string('status')->default('active');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Create a composite unique index for slug within a shop
            $table->unique(['shop_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_products');
    }
};

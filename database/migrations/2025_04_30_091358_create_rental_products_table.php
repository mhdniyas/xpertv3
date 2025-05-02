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
        Schema::create('rental_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('stock_quantity');
            $table->integer('available_quantity'); // Currently available for rent
            $table->string('unit')->default('piece');
            $table->string('status')->default('active'); // active, inactive, maintenance
            $table->string('condition')->default('good'); // new, good, fair, poor

            // Rental rates
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->decimal('weekly_rate', 10, 2)->nullable();
            $table->decimal('monthly_rate', 10, 2)->nullable();
            $table->decimal('deposit_amount', 10, 2)->default(0);

            $table->json('maintenance_history')->nullable();
            $table->json('images')->nullable(); // Multiple images as JSON array
            $table->string('primary_image')->nullable();

            $table->boolean('is_combo')->default(false); // Whether this is a combo product
            $table->json('combo_products')->nullable(); // Array of included product IDs if this is a combo

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_products');
    }
};

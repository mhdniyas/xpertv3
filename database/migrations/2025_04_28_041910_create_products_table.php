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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->foreignId('category_id')->constrained()->cascadeOnDelete();
                $table->foreignId('shop_id')->nullable()->constrained()->nullOnDelete();
                $table->boolean('is_global')->default(false);
                $table->boolean('is_active')->default(true);
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('rejection_reason')->nullable();
                $table->boolean('global_suggestion')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

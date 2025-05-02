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
        Schema::create('rental_customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // For registered users
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('id_type')->nullable(); // Type of ID provided (driver's license, etc.)
            $table->string('id_number')->nullable(); // ID number for verification
            $table->integer('total_rentals')->default(0);
            $table->integer('priority_points')->default(0);
            $table->string('customer_tier')->default('standard'); // standard, bronze, silver, gold, platinum
            $table->decimal('total_spent', 10, 2)->default(0);
            $table->json('rental_history')->nullable(); // Summary of past rentals as JSON
            $table->timestamp('last_rental_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_customer_profiles');
    }
};

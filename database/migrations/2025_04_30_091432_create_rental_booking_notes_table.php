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
        Schema::create('rental_booking_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('note_type')->default('general'); // general, pickup, return, damage, repair
            $table->text('note');
            $table->json('images')->nullable(); // Store additional images as JSON array
            $table->json('product_conditions')->nullable(); // Store specific product conditions as JSON
            $table->boolean('is_internal')->default(false); // Whether note is visible to customers
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_booking_notes');
    }
};

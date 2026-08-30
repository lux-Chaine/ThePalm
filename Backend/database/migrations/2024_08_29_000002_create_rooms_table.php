<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->enum('type', ['single', 'double', 'suite', 'deluxe', 'residential']);
            $table->decimal('price_per_night', 10, 2);
            $table->enum('status', ['available', 'booked', 'maintenance', 'cleaning'])->default('available');
            $table->integer('floor')->default(1);
            $table->integer('capacity')->default(2);
            $table->text('description')->nullable();
            $table->text('amenities')->nullable(); // JSON: wifi, tv, ac, etc.
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

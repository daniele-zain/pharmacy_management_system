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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacist_id')->nullable();
            $table->foreign('pharmacist_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('medicine_id')->nullable();
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');

            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');

            $table->decimal('quantity');
            $table->decimal('price');
            $table->boolean('payment');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

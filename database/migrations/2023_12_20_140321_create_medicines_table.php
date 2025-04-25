<?php

use App\Models\WarehouseManager;
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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('warehouse_manager_id')->nullable();
            $table->foreign('warehouse_manager_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('generic_name');
            $table->string('scientific_name')->nullable();
            $table->string('description');
            $table->decimal('price');
            $table->decimal('quantity');
            $table->string('company');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->date('expiration_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }


};

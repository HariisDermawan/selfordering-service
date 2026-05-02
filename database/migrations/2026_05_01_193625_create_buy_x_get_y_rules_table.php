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
        Schema::create('buy_x_get_y_rules', function (Blueprint $table) {
           $table->id();
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
            $table->foreignId('buy_product_id')->constrained('products')->onDelete('cascade');
            $table->integer('buy_quantity');
            $table->foreignId('get_product_id')->constrained('products')->onDelete('cascade');
            $table->integer('get_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buy_x_get_y_rules');
    }
};

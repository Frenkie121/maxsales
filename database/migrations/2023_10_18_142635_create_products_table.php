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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained(table: 'users');
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('store_id')->constrained();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->decimal('purchase_price');
            $table->decimal('sale_price');
            $table->decimal('day_price');
            $table->decimal('night_price');
            $table->decimal('weekend_price');
            $table->decimal('bonus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

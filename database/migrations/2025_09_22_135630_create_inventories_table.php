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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('item_name'); // e.g., Shampoo, Hair Dye
            $table->string('category'); // Haircare, Skincare, Tools
            $table->integer('quantity')->default(0); // Stock count
            $table->string('unit')->default('pieces'); // ml, bottles, boxes, etc.
            $table->integer('reorder_level')->default(0); // Alert threshold
            $table->string('supplier_name')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};

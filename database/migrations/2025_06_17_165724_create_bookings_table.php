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
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_variant_id')->nullable()->constrained()->nullOnDelete();

            $table->dateTime('appointment_time');

            $table->tinyInteger('status')->default(0); // 0 = pending, 1 = confirmed, etc.
            $table->tinyInteger('payment_status')->default(0); // 0 = unpaid, 1 = paid, etc.

            $table->text('cancel_reason')->nullable();

            // Pricing details
            $table->decimal('service_price', 10, 2);    // Price at booking time
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('addon_amount', 10, 2)->default(0); // Sum of selected addons
            $table->decimal('tip_amount', 10, 2)->default(0);    // Optional tip
            $table->decimal('payable_amount', 10, 2);            // service_price - discount + tax
            $table->decimal('total_amount', 10, 2);              // payable_amount + addons + tip

            $table->softDeletes();
            $table->timestamps();
            
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

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
            $table->foreignId('offer_id')->nullable()->constrained()->nullOnDelete();

            $table->dateTime('appointment_time');

            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('addon_amount', 10, 2)->default(0);

            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->nullable(); // Final amount to be charged

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('booking_status', ['pending', 'booked', 'in_progress', 'completed', 'cancelled'])->default('pending'); // More detailed status
            $table->tinyInteger('payment_status')->default(0); // 0 = unpaid, 1 = paid, etc.

            $table->enum('payment_method', ['cash', 'card', 'wallet', 'online'])->nullable(); // Useful for analytics

            $table->text('note')->nullable(); // Optional user or admin note (e.g., "Customer requested specific stylist")
            $table->text('cancel_reason')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

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

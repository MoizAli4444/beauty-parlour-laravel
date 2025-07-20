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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Linked to users table
            $table->foreignId('staff_role_id')->nullable()->constrained('staff_roles')->onDelete('set null');

            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('leaving_date')->nullable();
            $table->boolean('is_head')->default(false);

            $table->string('cnic')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('image')->nullable(); // profile photo

            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('set null');
            $table->json('working_days')->nullable(); // e.g. ["mon", "tue", "wed"]

            $table->decimal('salary', 10, 2)->nullable();
            $table->string('payment_schedule')->default('monthly'); // monthly, weekly, etc.

            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null');
            $table->string('bank_account_number')->nullable();

            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};

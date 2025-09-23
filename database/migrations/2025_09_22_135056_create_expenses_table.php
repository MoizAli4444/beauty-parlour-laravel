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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // who logged the expense (from users table)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // who/what the expense was paid to (can be admin, staff, employee, supplier, etc.)
            $table->morphs('userable'); // creates userable_id + userable_type

            // expense details
            $table->string('expense_type'); // Rent, Salary, Supplies, etc.
            $table->decimal('amount', 10, 2);

            // flexible payment methods
            $table->enum('payment_method', [
                'cash',
                'cheque',
                'online_payment'
            ])->default('cash');

            $table->date('date');
            $table->string('receipt_path')->nullable(); // optional receipt/proof
            $table->text('notes')->nullable();

            // soft delete + timestamps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

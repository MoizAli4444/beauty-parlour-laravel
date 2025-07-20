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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image')->nullable(); // Store the image filename or URL
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable()->default('pakistan');
            $table->string('postal_code')->nullable();
            $table->date('date_of_birth')->nullable(); // For birthday offers
            
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // Optional
            $table->boolean('email_verified')->default(false); // Optional
            // $table->boolean('sms_subscribed')->default(true); // Optional: For SMS marketing preferences

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
        Schema::dropIfExists('customers');
    }
};

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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable(); // make any phone/email field nullable
            $table->string('email')->nullable(); // make any phone/email field nullable
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');
            $table->enum('status', ['pending', 'resolved', 'closed'])->default('pending');
            $table->text('response')->nullable(); // reply from admin
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};

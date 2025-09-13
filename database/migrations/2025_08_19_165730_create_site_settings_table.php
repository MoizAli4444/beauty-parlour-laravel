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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('favicon')->nullable();

            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('working_hours')->nullable();

            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();

            $table->string('currency', 10)->default('PKR'); // can be PKR, USD etc.
            $table->string('default_image')->nullable();    // fallback image

            // 🔵 Optional fields for later
            // $table->string('whatsapp_number')->nullable();
            // $table->string('support_email')->nullable();
            // $table->text('meta_title')->nullable();
            // $table->text('meta_description')->nullable();
            // $table->text('booking_terms')->nullable();
            // $table->text('cancellation_policy')->nullable();
            // $table->json('payment_methods')->nullable();
            // $table->string('youtube_link')->nullable();
            // $table->string('tiktok_link')->nullable();
            // $table->text('about_us')->nullable();
            // $table->text('google_map_embed')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};

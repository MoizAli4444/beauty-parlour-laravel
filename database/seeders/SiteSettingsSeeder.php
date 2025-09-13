<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::create([
            'site_name'       => 'Glamour Beauty Salon',
            'site_logo'       => 'uploads/settings/logo.png',
            'favicon'         => 'uploads/settings/favicon.ico',

            'contact_email'   => 'info@glamourbeauty.pk',
            'contact_phone'   => '+92 321 1234567',
            'contact_address' => 'DHA Phase 6, Karachi, Pakistan',
            'working_hours'   => 'Mon-Sun: 10:00 AM – 9:00 PM',

            'facebook_link'   => 'https://facebook.com/glamourbeauty.pk',
            'instagram_link'  => 'https://instagram.com/glamourbeauty.pk',

            'currency'        => 'PKR',
            'default_image'   => 'uploads/settings/default.png',
        ]);
    }
}

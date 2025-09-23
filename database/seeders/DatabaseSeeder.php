<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'role' => 'admin'
        // ]);

        $this->call([

            RolesAndPermissionsSeeder::class,

            ServiceSeeder::class,
            ServiceVariantSeeder::class,

            PaymentMethodSeeder::class,
            ShiftSeeder::class,
            StaffRoleSeeder::class,
            StaffSeeder::class,
            CustomerSeeder::class,

            OfferSeeder::class,

            AddonsTableSeeder::class,
            // booking seeder missing
            BookingReviewSeeder::class,
            GallerySeeder::class,
            // deal seeder missing
            FaqSeeder::class,
            ContactMessageSeeder::class,
            TestimonialSeeder::class,
            BlogPostsSeeder::class,

            ExpenseSeeder::class,



        ]);
    }
}

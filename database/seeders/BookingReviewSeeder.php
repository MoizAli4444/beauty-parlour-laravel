<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingReview;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $customers = Customer::take(5)->get();
        $bookings  = Booking::take(10)->get();

        if ($customers->isEmpty() || $bookings->isEmpty()) {
            $this->command->warn('⚠️ No customers or bookings found to attach reviews.');
            return;
        }

        foreach ($bookings as $booking) {
            $customer = $customers->random();

            // Randomly pick moderator type (either admin user or staff)
            if (rand(0, 1)) {
                // Moderator is an admin user
                $moderator = User::where('role', 'admin')->inRandomOrder()->first();
                $moderatorType = User::class;
            } else {
                // Moderator is a staff member
                $moderator = Staff::inRandomOrder()->first();
                $moderatorType = Staff::class;
            }

            BookingReview::create([
                'booking_id'     => $booking->id,
                'customer_id'    => $customer->id,
                'rating'         => rand(3, 5),
                'review'         => fake()->sentence(12),
                'status'         => ['pending', 'approved', 'rejected'][rand(0, 2)],
                'moderator_id'   => $moderator?->id,
                'moderator_type' => $moderator ? $moderatorType : null,
            ]);
        }

        $this->command->info('✅ Booking reviews seeded successfully.');
    }
}

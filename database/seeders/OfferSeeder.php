<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Offer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Offer::truncate(); // Optional: clear previous data

        $offers = [
            [
                'name' => 'Summer Discount',
                'slug' => Str::slug('Summer Discount'),
                'description' => 'Get 20% off on all services this summer!',
                'type' => 'percentage',
                'value' => 20.00,
                'starts_at' => Carbon::now()->subDays(2),
                'ends_at' => Carbon::now()->addDays(30),
                'max_total_uses' => 500,
                'max_uses_per_user' => 1,
                'offer_code' => 'SUMMER20',
                'image' => 'offers/summer-discount.png',
                'status' => 'active',
                'lifecycle' => 'active',
                'created_by' => 1, // replace with valid user ID
                'updated_by' => 1,
            ],
            [
                'name' => 'Flat 100 Off',
                'slug' => Str::slug('Flat 100 Off'),
                'description' => 'Get a flat ₹100 discount on selected services.',
                'type' => 'flat',
                'value' => 100.00,
                'starts_at' => Carbon::now()->subDays(5),
                'ends_at' => Carbon::now()->addDays(10),
                'max_total_uses' => 1000,
                'max_uses_per_user' => 2,
                'offer_code' => 'FLAT100',
                'image' => 'offers/flat-100.png',
                'status' => 'active',
                'lifecycle' => 'active',
                'created_by' => 1, // replace with valid user ID
                'updated_by' => 1,
            ],
            [
                'name' => 'Upcoming Mega Deal',
                'slug' => Str::slug('Upcoming Mega Deal'),
                'description' => 'Stay tuned for our biggest discount yet!',
                'type' => 'percentage',
                'value' => 50.00,
                'starts_at' => Carbon::now()->addDays(5),
                'ends_at' => Carbon::now()->addDays(15),
                'max_total_uses' => 100,
                'max_uses_per_user' => 1,
                'offer_code' => null,
                'image' => 'offers/mega-deal.png',
                'status' => 'inactive',
                'lifecycle' => 'upcoming',
                'created_by' => 1, // replace with valid user ID
                'updated_by' => 1,
            ],
        ];

        foreach ($offers as $offer) {
            Offer::create($offer);
        }
    }
}

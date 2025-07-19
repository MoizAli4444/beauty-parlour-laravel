<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $services = [
            'Haircuts & Styling',
            'Facial Treatments',
            'Party & Bridal Makeup',
            'Manicure & Pedicure',
            'Threading & Waxing',
            'Hair Coloring',
            'Hair Rebonding & Keratin',
            'Body Massage',
            'Nail Art & Extensions',
            'Skin Whitening Treatment',
        ];

        $statuses = ['active', 'inactive'];

        foreach ($services as $index => $name) {
            Service::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => fake()->sentence(12), // short but meaningful
                'image' => 'services/sample' . rand(1, 5) . '.jpg', // ensure these sample images exist
                'status' => $statuses[array_rand($statuses)],
                'created_by' => 1, // replace with valid user ID
                'updated_by' => 1,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Service;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Factory::create();

        $services = Service::pluck('id')->toArray();
        $users    = User::pluck('id')->toArray();

        foreach (range(1, 20) as $i) {
            Gallery::create([
                'service_id'  => $faker->randomElement($services) ?? null,
                'title'       => $faker->sentence(3),
                'description' => $faker->paragraph,
                'file_path'   => 'uploads/gallery/' . $faker->uuid . '.jpg', // just dummy path
                'media_type'  => $faker->randomElement(['image', 'video']),
                'featured'    => $faker->boolean(20), // 20% chance featured
                'alt_text'    => $faker->words(3, true),
                'file_size'   => $faker->numberBetween(50000, 5000000), // 50KB - 5MB
                'status'      => $faker->randomElement(['active', 'inactive']),
                'created_by'  => $faker->randomElement($users) ?? null,
                'updated_by'  => $faker->randomElement($users) ?? null,
            ]);
        }
    }
}

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
        $statuses = ['active', 'inactive'];

        for ($i = 0; $i < 10; $i++) {
            $name = fake()->unique()->words(2, true); // e.g. "Bridal Makeup"

            Service::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => fake()->paragraph(),
                'image' => 'services/sample' . rand(1, 5) . '.jpg',
                'status' => $statuses[array_rand($statuses)],
                'created_by' => rand(1, 1), // make sure user IDs exist
                'updated_by' => rand(1, 1),
            ]);
        }
    }
}

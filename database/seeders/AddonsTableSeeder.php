<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Addon;

class AddonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addons = [
            [
                'name' => 'Basic Haircut',
                'slug' => Str::slug('Basic Haircut'),
                'description' => 'A standard haircut service.',
                'image' => null,
                'price' => 10.00,
                'duration_minutes' => 30,
                'status' => 1,
                'gender' => 2, // Both
            ],
            [
                'name' => 'Beard Trim',
                'slug' => Str::slug('Beard Trim'),
                'description' => 'Beard shaping and trimming.',
                'image' => null,
                'price' => 5.00,
                'duration_minutes' => 15,
                'status' => 1,
                'gender' => 1, // Male
            ],
            [
                'name' => 'Hair Wash',
                'slug' => Str::slug('Hair Wash'),
                'description' => 'Gentle cleansing and conditioning.',
                'image' => null,
                'price' => 7.50,
                'duration_minutes' => 20,
                'status' => 1,
                'gender' => 2, // Both
            ],
            [
                'name' => 'Facial Treatment',
                'slug' => Str::slug('Facial Treatment'),
                'description' => 'Deep cleansing facial for glowing skin.',
                'image' => null,
                'price' => 15.00,
                'duration_minutes' => 40,
                'status' => 1,
                'gender' => 0,
            ],
            [
                'name' => 'Nail Art',
                'slug' => Str::slug('Nail Art'),
                'description' => 'Stylish and trendy nail designs.',
                'image' => null,
                'price' => 12.50,
                'duration_minutes' => 30,
                'status' => 1,
                'gender' => 0,
            ],
        ];

        foreach ($addons as $addon) {
            Addon::create($addon);
        }
    }
}

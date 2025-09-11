<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Sarah Khan',
                'designation' => 'Regular Customer',
                'testimonial' => 'I had an amazing experience at the salon! The staff is very friendly and professional.',
                'image' => 'testimonials/sarah.jpg',
                'status' => 'active',
            ],
            [
                'name' => 'Ali Raza',
                'designation' => 'Student',
                'testimonial' => 'Great service and relaxing environment. Highly recommend their facial treatments!',
                'image' => 'testimonials/ali.png',
                'status' => 'active',
            ],
            [
                'name' => 'Maria Ahmed',
                'designation' => 'Working Professional',
                'testimonial' => 'Loved the haircut I got! The stylist really understood what I wanted.',
                'image' => null,
                'status' => 'inactive',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}

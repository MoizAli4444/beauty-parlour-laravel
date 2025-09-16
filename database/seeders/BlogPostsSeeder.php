<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class BlogPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        // Make sure you have at least one user and one service
        $user = User::first() ?? User::factory()->create();
        $service = Service::first() ?? Service::factory()->create([
            'name' => 'Hair Spa',
            'description' => 'Relaxing hair spa service with nourishing oils.',
        ]);

        $posts = [
            [
                'title' => 'Top 5 Hair Care Tips for Healthy Hair',
                'excerpt' => 'Discover expert tips to keep your hair shiny, smooth, and healthy.',
                'content' => '<p>Taking care of your hair is essential for maintaining its natural shine and strength. Here are the top 5 tips from our salon experts...</p>',
                'image' => 'uploads/blogs/hair_tips.jpg',
                'author_id' => $user->id,
                'service_id' => $service->id,
                'published_at' => Carbon::now()->subDays(5),
                'status' => 'published',
                'views' => 120,
            ],
            [
                'title' => 'Bridal Makeup Trends 2025',
                'excerpt' => 'Stay updated with the latest bridal makeup looks for your big day.',
                'content' => '<p>Our bridal experts have highlighted the hottest makeup styles for 2025 brides, from soft glam to bold red lips...</p>',
                'image' => 'uploads/blogs/bridal_makeup.jpg',
                'author_id' => $user->id,
                'service_id' => $service->id,
                'published_at' => Carbon::now()->subDays(2),
                'status' => 'published',
                'views' => 200,
            ],
            [
                'title' => 'Why Regular Facials Are Important',
                'excerpt' => 'Facials not only relax you but also improve your skin health.',
                'content' => '<p>Many clients overlook facials, but they provide deep cleansing, hydration, and rejuvenation for your skin...</p>',
                'image' => 'uploads/blogs/facials.jpg',
                'author_id' => $user->id,
                'service_id' => $service->id,
                'published_at' => null, // still draft
                'status' => 'draft',
                'views' => 0,
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                $post + ['slug' => Str::slug($post['title'])]
            );
        }
    }
}

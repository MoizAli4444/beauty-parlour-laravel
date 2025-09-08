<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'How can I reset my password?',
                'answer' => 'You can reset your password by clicking on the "Forgot Password" link on the login page and following the instructions.',
                'status' => 'active',
            ],
            [
                'question' => 'How do I contact support?',
                'answer' => 'You can contact support by emailing support@example.com or using the support form on our website.',
                'status' => 'active',
            ],
            [
                'question' => 'Can I change my subscription plan?',
                'answer' => 'Yes, you can change your subscription plan from your account settings under the "Subscription" section.',
                'status' => 'inactive',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create([
                'question' => $faq['question'],
                'slug' => Str::slug($faq['question']),
                'answer' => $faq['answer'],
                'status' => $faq['status'],
            ]);
        }
    }
}

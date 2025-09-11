<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            [
                'name' => 'John Doe',
                'phone' => '1234567890',
                'email' => 'john@example.com',
                'subject' => 'Booking Inquiry',
                'message' => 'I would like to book a facial appointment for next week.',
                'priority' => 'medium',
                'status' => 'pending',
                'response' => null,
            ],
            [
                'name' => 'Jane Smith',
                'phone' => null,
                'email' => 'jane@example.com',
                'subject' => 'Complaint',
                'message' => 'I had a bad experience during my last visit, please contact me.',
                'priority' => 'high',
                'status' => 'closed',
                'response' => null,
            ],
            [
                'name' => 'Ali Khan',
                'phone' => '9876543210',
                'email' => null,
                'subject' => null,
                'message' => 'Can you provide details about your bridal packages?',
                'priority' => 'low',
                'status' => 'in_progress',
                'response' => 'Hello Ali, yes we provide complete bridal packages. Please visit our packages page.',
            ],
        ];

        foreach ($messages as $message) {
            ContactMessage::create([
                'name' => $message['name'],
                'phone' => $message['phone'],
                'email' => $message['email'],
                'subject' => $message['subject'],
                'message' => $message['message'],
                'priority' => $message['priority'],
                'status' => $message['status'],
                'response' => $message['response'],
            ]);
        }
    }
}

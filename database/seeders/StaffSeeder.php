<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\Shift;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shift = Shift::first();
        $paymentMethod = PaymentMethod::first();

        for ($i = 1; $i <= 2; $i++) {
            $user = User::create([
                'name' => "Staff User $i",
                'email' => "staff$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'staff',
            ]);

            Staff::create([
                'user_id' => $user->id,
                'staff_role_id' => 1, // adjust if needed
                'phone' => '0300123456' . $i,
                'address' => 'Main Street ' . $i,
                'date_of_birth' => '1995-01-0' . $i,
                'joining_date' => now(),
                'is_head' => $i == 1, // first one is head
                'cnic' => "12345-123456$i-1",
                'emergency_contact' => '0311123456' . $i,
                'image' => null,
                'shift_id' => $shift->id, // adjust if needed
                'working_days' => json_encode(['mon', 'tue', 'wed']),
                'salary' => 50000 + ($i * 1000),
                'payment_schedule' => 'monthly',
                'payment_method_id' => $paymentMethod->id, // adjust if needed
                'bank_account_number' => '12345678901' . $i,
                'is_verified' => true,
                'status' => 'active',
            ]);
        }
    }
}

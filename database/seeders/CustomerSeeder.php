<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            $user = User::create([
                'name' => "Customer User $i",
                'email' => "customer$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]);

            Customer::create([
                'user_id' => $user->id,
                'image' => null,
                'phone' => '0321123456' . $i,
                'address' => 'House ' . (120 + $i) . ', Street 5',
                'city' => 'Lahore',
                'country' => 'Pakistan',
                'postal_code' => '5400' . $i,
                'date_of_birth' => '1990-05-0' . (4 + $i),
                'gender' => $i % 2 === 0 ? 'female' : 'male',
                'email_verified' => true,
                'status' => 'active',
            ]);
        }
    }
}

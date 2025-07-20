<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = ['Cash', 'Bank Transfer', 'Card', 'Online'];

        foreach ($methods as $method) {
            PaymentMethod::create([
                'name' => $method,
                'slug' => Str::slug($method),
                'description' => $method . ' payment method',
                'status' => 'active',
            ]);
        }
    }
}

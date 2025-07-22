<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('customer');

        $customerData = [
            'user_id' => $user->id,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'country' => $data['country'] ?? 'pakistan',
            'postal_code' => $data['postal_code'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
        ];

        if (isset($data['image'])) {
            $customerData['image'] = $data['image']->store('customer-images', 'public');
        }

        return Customer::create($customerData);
    }
}

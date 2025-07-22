<?php

namespace App\Repositories\Staff;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('staff'); // Assign role

        $staffData = [
            'user_id' => $user->id,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'joining_date' => $data['joining_date'] ?? null,
            'leaving_date' => $data['leaving_date'] ?? null,
            'is_head' => $data['is_head'] ?? false,
            'cnic' => $data['cnic'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'shift_id' => $data['shift_id'] ?? null,
            'working_days' => $data['working_days'] ?? null,
            'salary' => $data['salary'] ?? null,
            'payment_schedule' => $data['payment_schedule'] ?? 'monthly',
            'payment_method_id' => $data['payment_method_id'] ?? null,
            'bank_account_number' => $data['bank_account_number'] ?? null,
        ];

        if (isset($data['image'])) {
            $staffData['image'] = $data['image']->store('staff-images', 'public');
        }

        return Staff::create($staffData);
    }
}

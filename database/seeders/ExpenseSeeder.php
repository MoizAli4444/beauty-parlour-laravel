<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\User;
use App\Models\Staff;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = Staff::first();
        $user    = User::first();

        $expenses = [
            [
                'expense_type'   => 'Salary',
                'amount'         => 50000,
                'payment_method' => 'online_payment',
                'date'           => Carbon::now()->subDays(10),
                'notes'          => 'September salary paid',
                'moderator'      => $staff,
            ],
            [
                'expense_type'   => 'Office Rent',
                'amount'         => 20000,
                'payment_method' => 'cheque',
                'date'           => Carbon::now()->subDays(20),
                'notes'          => 'Monthly shop rent',
                'moderator'      => $user,
            ],
            [
                'expense_type'   => 'Electricity Bill',
                'amount'         => 8000,
                'payment_method' => 'cash',
                'date'           => Carbon::now()->subDays(5),
                'notes'          => 'Paid to K-Electric',
                'moderator'      => null,
            ],
            [
                'expense_type'   => 'Water Bill',
                'amount'         => 3000,
                'payment_method' => 'cash',
                'date'           => Carbon::now()->subDays(7),
                'notes'          => 'Water utility payment',
                'moderator'      => null,
            ],
            [
                'expense_type'   => 'Internet Bill',
                'amount'         => 4500,
                'payment_method' => 'online_payment',
                'date'           => Carbon::now()->subDays(6),
                'notes'          => 'Paid to ISP',
                'moderator'      => $user,
            ],
            [
                'expense_type'   => 'Cleaning Supplies',
                'amount'         => 2500,
                'payment_method' => 'cash',
                'date'           => Carbon::now()->subDays(3),
                'notes'          => 'Bought cleaning materials',
                'moderator'      => $staff,
            ],
            [
                'expense_type'   => 'Salon Products',
                'amount'         => 12000,
                'payment_method' => 'online_payment',
                'date'           => Carbon::now()->subDays(15),
                'notes'          => 'Hair and beauty products',
                'moderator'      => $user,
            ],
            [
                'expense_type'   => 'Maintenance',
                'amount'         => 7000,
                'payment_method' => 'cheque',
                'date'           => Carbon::now()->subDays(12),
                'notes'          => 'AC repair and maintenance',
                'moderator'      => null,
            ],
        ];

        foreach ($expenses as $exp) {
            $expense = Expense::create([
                'expense_type'   => $exp['expense_type'],
                'amount'         => $exp['amount'],
                'payment_method' => $exp['payment_method'],
                'date'           => $exp['date'],
                'notes'          => $exp['notes'],
            ]);

            if ($exp['moderator']) {
                $expense->moderator()->associate($exp['moderator'])->save();
            }
        }
    }
}

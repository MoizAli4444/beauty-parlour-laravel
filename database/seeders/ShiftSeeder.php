<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            ['name' => 'Morning', 'start' => '08:00', 'end' => '14:00'],
            ['name' => 'Evening', 'start' => '14:00', 'end' => '20:00'],
            ['name' => 'Full-Time', 'start' => '08:00', 'end' => '20:00'],
        ];

        foreach ($shifts as $shift) {
            Shift::create([
                'name' => $shift['name'],
                'slug' => Str::slug($shift['name']),
                'description' => $shift['name'] . ' shift',
                'start_time' => $shift['start'],
                'end_time' => $shift['end'],
                'status' => 'active',
            ]);
        }
    }
}

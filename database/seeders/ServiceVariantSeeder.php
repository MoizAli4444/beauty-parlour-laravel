<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $services = Service::all();

        if ($services->isEmpty()) {
            $this->command->warn('No services found. Please seed the services table first.');
            return;
        }

        foreach ($services as $service) {
            for ($i = 1; $i <= 3; $i++) {
                $name = $service->name . " Variant " . $i;

                ServiceVariant::create([
                    'service_id'  => $service->id,
                    'image'       => null, // You can set a default image path if needed
                    'name'        => $name,
                    'slug'        => Str::slug($name . '-' . Str::random(5)),
                    'description' => 'Description for ' . $name,
                    'price'       => rand(100, 1000),
                    'duration'    => rand(30, 120) . ' mins',
                    'status'      => 'active',
                ]);
            }
        }
    }
}

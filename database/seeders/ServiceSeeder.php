<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;
use Faker\Factory as Faker;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $categories = ServiceCategory::all();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 3; $i++) {
                Service::create([
                    'category_id' => $category->id,
                    'service_title' => $faker->sentence(3), // عنوان عشوائي
                    'service_desc' => $faker->paragraph,     // وصف عشوائي
                ]);
            }
        }
    }
}


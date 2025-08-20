<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\ServiceCategorySeeder;
use Database\Seeders\ServiceSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // إنشاء مستخدم تجريبي
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // استدعاء Seeders للفئات والخدمات
        $this->call([
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
        ]);
    }
}

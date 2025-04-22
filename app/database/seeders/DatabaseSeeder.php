<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('productsAR')->insert([
            [
                'name' => 'Elegant Gold Ring',
                'description' => 'A beautifully crafted gold ring with a unique design.',
                'price' => 250.00,
                'image_url' => asset('rings/1.jpg'),
                'model_url' => asset('1.glb'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Silver Diamond Ring',
                'description' => 'A stylish silver ring with a sparkling diamond.',
                'price' => 320.00,
                'image_url' => asset('rings/2.jpg'),
                'model_url' => asset('2.glb'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rose Gold Engagement Ring',
                'description' => 'A romantic rose gold engagement ring with a beautiful stone.',
                'price' => 400.00,
                'image_url' => asset('rings/3.jpg'),
                'model_url' => asset('3.glb'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Classic Platinum Band',
                'description' => 'A classic platinum wedding band with a smooth finish.',
                'price' => 280.00,
                'image_url' => asset('rings/4.jpg'),
                'model_url' => asset('4.glb'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

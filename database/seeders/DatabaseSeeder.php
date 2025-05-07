<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Tim',
            'email' => 'tim26618@gmail.com',
        ]);


        // Brand::factory()->create([
        //     'name' => 'Apple',
        //     'slug' => 'apple',
        // ]);
        // Brand::factory()->create([
        //     'name' => 'Samsung',
        //     'slug' => 'samsung',
        // ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['code' => 'SOFTWARE', 'name' => 'Software/Apps'],
            ['code' => 'HARDWARE', 'name' => 'Hardware/Robótica'],
            ['code' => 'SOCIAL', 'name' => 'Innovación Social'],
            ['code' => 'INVESTIGACION', 'name' => 'Investigación'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['code' => $category['code']], $category);
        }
    }
}

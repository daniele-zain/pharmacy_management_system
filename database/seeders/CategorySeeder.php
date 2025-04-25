<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'id'=>1,
            'class' => 'A'
           ]);
            Category::create([
            'id'=>2,
            'class' => 'A10'
           ]);
            Category::create([
            'id'=>3,
            'class' => 'A10B'
           ]);
            Category::create([
            'id'=>4,
            'class' => 'A10BA'
            ]);
            Category::create([
            'id'=>5,
            'class' => 'A10BA02'
            ]);
    }
}

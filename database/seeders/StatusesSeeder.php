<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
    */

    public function run(): void
    {
        Status::create([
        'id'=>1,
        'name' => 'in_process'
        ]);
        Status::create([
         'id'=>2,
         'name' => 'sent'
        ]);
        Status::create([
         'id'=>3,
         'name' => 'delivered'
        ]);
    }
}

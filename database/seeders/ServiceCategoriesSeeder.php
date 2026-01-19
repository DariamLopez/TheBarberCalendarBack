<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceCategory::insert([
            ['name' => 'Haircuts'],
            ['name' => 'Manicure'],
            ['name' => 'Pedicure'],
            ['name' => 'Eyesbrows'],
            ['name' => 'Makeup'],
        ]);
    }
}

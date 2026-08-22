<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::updateOrCreate(
            ['name' => 'Infant'],
            [
                'min_age' => 0,
                'max_age' => 4,
            ]
        );

        Category::updateOrCreate(
            ['name' => 'Kids'],
            [
                'min_age' => 5,
                'max_age' => 8,
            ]
        );

        Category::updateOrCreate(
            ['name' => 'Adult'],
            [
                'min_age' => 9,
                'max_age' => 59,
            ]
        );

        Category::updateOrCreate(
            ['name' => 'Senior'],
            [
                'min_age' => 60,
                'max_age' => null,
            ]
        );

        Category::updateOrCreate(
            ['name' => 'Free'],
            [
                'min_age' => 0,
                'max_age' => null,
            ]
        );
    }
}

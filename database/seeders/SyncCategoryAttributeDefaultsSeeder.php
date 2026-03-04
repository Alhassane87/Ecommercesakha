<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Services\CategoryAttributeService;
use Illuminate\Database\Seeder;

class SyncCategoryAttributeDefaultsSeeder extends Seeder
{
    public function run(): void
    {
        Category::query()->each(function (Category $category) {
            CategoryAttributeService::setDefaultAttributesForCategory($category);
        });
    }
}


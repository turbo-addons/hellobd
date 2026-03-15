<?php

namespace Database\Seeders;

use App\Models\Term;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Uncategorized', 'name_bn' => 'অবর্গীকৃত', 'slug' => 'uncategorized', 'color' => '#9CA3AF'],
            ['name' => 'Bangladesh', 'name_bn' => 'বাংলাদেশ', 'slug' => 'bangladesh', 'color' => '#DC2626'],
            ['name' => 'Politics', 'name_bn' => 'রাজনীতি', 'slug' => 'politics', 'color' => '#7C3AED'],
            ['name' => 'International', 'name_bn' => 'বিশ্ব', 'slug' => 'international', 'color' => '#059669'],
            ['name' => 'Economy', 'name_bn' => 'অর্থ-বাণিজ্য', 'slug' => 'economy', 'color' => '#0284C7'],
            ['name' => 'Entertainment', 'name_bn' => 'বিনোদন', 'slug' => 'entertainment', 'color' => '#EA580C'],
            ['name' => 'Opinion', 'name_bn' => 'মতামত', 'slug' => 'opinion', 'color' => '#6B7280'],
            ['name' => 'Sports', 'name_bn' => 'খেলা', 'slug' => 'sports', 'color' => '#EC4899'],
            ['name' => 'Jobs', 'name_bn' => 'চাকরি', 'slug' => 'jobs', 'color' => '#10B981'],
            ['name' => 'Trending', 'name_bn' => 'আলোচিত', 'slug' => 'trending', 'color' => '#F59E0B'],
            ['name' => 'Technology', 'name_bn' => 'প্রযুক্তি', 'slug' => 'technology', 'color' => '#3B82F6'],
            ['name' => 'Business', 'name_bn' => 'ব্যবসা', 'slug' => 'business', 'color' => '#8B5CF6'],
            ['name' => 'Education', 'name_bn' => 'শিক্ষা', 'slug' => 'education', 'color' => '#14B8A6'],
        ];

        foreach ($categories as $category) {
            Term::updateOrCreate(
                ['slug' => $category['slug'], 'taxonomy' => 'category'],
                [
                    'name' => $category['name'],
                    'name_bn' => $category['name_bn'],
                    'color' => $category['color'],
                ]
            );
        }

        $this->command->info('Categories created successfully!');
    }
}

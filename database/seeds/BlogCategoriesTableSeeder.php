<?php

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'title' => $title = 'Freelancing',
                'slug' => str_slug($title),
            ],
            [
                'title' => $title = 'Entrepreneurship',
                'slug' => str_slug($title),
            ],
            [
                'title' => $title = 'Development',
                'slug' => str_slug($title),
            ],
            [
                'title' => $title = 'Design',
                'slug' => str_slug($title),
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::create($category);
        }
    }
}

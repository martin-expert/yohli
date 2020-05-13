<?php

use App\Models\BlogTag;
use Illuminate\Database\Seeder;

class BlogTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            [
                'title' => $title = 'employment',
                'slug' => str_slug($title),
            ],
            [
                'title' => $title = 'marketing',
                'slug' => str_slug($title),
            ],
            [
                'title' => $title = 'branding',
                'slug' => str_slug($title),
            ],
            [
                'title' => $title = 'design',
                'slug' => str_slug($title),
            ],
        ];

        foreach ($tags as $tag) {
            BlogTag::create($tag);
        }
    }
}

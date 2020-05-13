<?php

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BlogPostsTableSeeder extends Seeder
{
    public function randomFeatured()
    {
        $data = ['yes', 'no'];
        return $data[rand(0, 1)];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create(BlogPost::class);

        for ($i = 1; $i <= 30; $i++) {
            $post = [
                'user_id' => 1,
                'title' => $title = $faker->sentence(),
                'slug' => str_slug($title),
                'body' => $faker->paragraph(),
                'featured' => self::randomFeatured()
            ];

            $blog = BlogPost::create($post);
            $blog->categories()->attach([$faker->numberBetween(1, 4)]);
            $blog->tags()->attach([$faker->numberBetween(1, 4)]);
        }
    }
}

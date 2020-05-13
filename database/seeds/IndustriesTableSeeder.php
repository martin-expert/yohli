<?php

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = [
            [
                'title' => 'Admin Support',
                'featured' => 'yes',
            ],
            [
                'title' => 'Customer Service',
                'featured' => 'yes',
            ],
            [
                'title' => 'Data Analytics',
                'featured' => 'yes',
            ],
            [
                'title' => 'Graphics Design',
                'featured' => 'yes',
            ],
            [
                'title' => 'Software Development',
                'featured' => 'yes',
            ],
            [
                'title' => 'IT & Networking',
                'featured' => 'yes',
            ],
            [
                'title' => 'Writing',
                'featured' => 'yes',
            ],
            [
                'title' => 'Translation',
                'featured' => 'yes',
            ],
            [
                'title' => 'Sales & Marketing',
                'featured' => 'yes',
            ],
        ];

        foreach ($industries as $industry) {
            Industry::create($industry);
        }
    }
}

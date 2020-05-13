<?php

use App\Models\Job;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;


class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $jobs = [
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 5,
                'country_id' => 80,
                'job_budget_id' => 1,
                'title' => $title = 'Food Delivery Mobile Application',
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '6',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'fixed',
                'min_budget' => 1000,
                'max_budget' => 2000,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 8,
                'country_id' => 80,
                'job_budget_id' => 2,
                'title' => $title = '2000 Words English to German',
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '2',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'hourly',
                'min_budget' => 75,
                'max_budget' => 200,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 5,
                'country_id' => 80,
                'job_budget_id' => 1,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '6',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'fixed',
                'min_budget' => 1000,
                'max_budget' => 2000,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 8,
                'country_id' => 80,
                'job_budget_id' => 2,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '2',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'hourly',
                'min_budget' => 75,
                'max_budget' => 200,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 5,
                'country_id' => 80,
                'job_budget_id' => 1,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '6',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'fixed',
                'min_budget' => 1000,
                'max_budget' => 2000,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 8,
                'country_id' => 80,
                'job_budget_id' => 2,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '2',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'hourly',
                'min_budget' => 75,
                'max_budget' => 200,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 5,
                'country_id' => 80,
                'job_budget_id' => 1,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '6',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'fixed',
                'min_budget' => 1000,
                'max_budget' => 2000,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 8,
                'country_id' => 80,
                'job_budget_id' => 2,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '2',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'hourly',
                'min_budget' => 75,
                'max_budget' => 200,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 5,
                'country_id' => 80,
                'job_budget_id' => 1,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '6',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'fixed',
                'min_budget' => 1000,
                'max_budget' => 2000,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 8,
                'country_id' => 80,
                'job_budget_id' => 2,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '2',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'hourly',
                'min_budget' => 75,
                'max_budget' => 200,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 5,
                'country_id' => 80,
                'job_budget_id' => 1,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '6',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'fixed',
                'min_budget' => 1000,
                'max_budget' => 2000,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 8,
                'country_id' => 80,
                'job_budget_id' => 2,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '2',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'hourly',
                'min_budget' => 75,
                'max_budget' => 200,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 5,
                'country_id' => 80,
                'job_budget_id' => 1,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '6',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'fixed',
                'min_budget' => 1000,
                'max_budget' => 2000,
            ],
            [
                'user_id' => 2,
                'uuid' => Str::uuid(),
                'industry_id' => 8,
                'country_id' => 80,
                'job_budget_id' => 2,
                'title' => $title = $faker->lexify('????? ???? job') ,
                'slug' => str_slug($title),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '2',
                'featured' => 'yes',
                'status' => 'not assigned',
                'budget_type' => 'hourly',
                'min_budget' => 75,
                'max_budget' => 200,
            ],

        ];


        foreach ($jobs as $j) {
            $job = Job::create($j);

            if ($job->id == 1) {
                $job->skills()->attach([1, 2]);
            } else {
                $job->skills()->attach([3, 4, 5]);
            }
        }
    }
}

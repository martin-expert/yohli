<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [

            [
                'first_name' => 'Emmanuel',
                'last_name' => 'Oduro',
                'password' => bcrypt('decount'),
                'email' => 'emmarthurson@gmail.com',
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Emmanuel',
                'last_name' => 'Oduro',
                'password' => bcrypt('decount'),
                'email' => 'emmanuel@jumeni.com',
                'email_verified_at' => now(),
            ],

        ];



        foreach ($users as $user) {
            $user = User::create($user);

            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->name = $user->first_name . " " .$user->last_name;
            $profile->phone = $user->phone;
            $profile->email = $user->email;
            $profile->country_id = 80;
            $profile->rate = 60;
            $profile->headline = "Flutter Ninja + Laravel Superman";
            $profile->description = "Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.
    
            Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.";
            $profile->type = $user->id == 1 ? 'freelancer' : 'hirer';
            $profile->save();

            if ($user->id == 1) {
                $profile->skills()->attach([1, 2, 6, 7, 8, 9, 10, 11]);
            }

            $user->id == 1 ? $user->assignRole('freelancer') : $user->assignRole('hirer');
        }
    }
}

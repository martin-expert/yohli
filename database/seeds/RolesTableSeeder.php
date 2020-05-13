<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder 
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'freelancer', 
                'guard_name' => 'web', 
            ],
            [
                'name' => 'hirer',
                'guard_name' => 'web', 
            ],
            
        ];

        foreach($roles as $role){
            Role::create($role);
        }
    }
}

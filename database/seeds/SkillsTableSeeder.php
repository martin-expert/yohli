<?php

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('skills')->truncate();

        Maatwebsite\Excel\Facades\Excel::import(new App\Imports\SkillImport, storage_path('skillset.csv'));

    }
}

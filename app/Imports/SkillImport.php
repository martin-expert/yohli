<?php

namespace App\Imports;

use App\Models\Skill;
use Maatwebsite\Excel\Concerns\ToModel;

class SkillImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Skill([
            'title' => $row[0],
        ]);
    }

    public function batchSize(): int
    {
        return 100;
    }
    
    public function chunkSize(): int
    {
        return 100;
    }
}

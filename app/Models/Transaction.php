<?php

namespace App\Models;

use App\Traits\Uuid;
use DigitalCloud\ModelNotes\HasNotes;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasNotes, Uuid;
    
    protected $table = 'transactions';
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }


}


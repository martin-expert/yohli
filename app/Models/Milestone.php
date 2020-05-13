<?php

namespace App\Models;

use App\Traits\Uuid;
use DigitalCloud\ModelNotes\HasNotes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends Model
{

    use HasNotes, Uuid;

    /**
    * @var  string
    */
    protected $table = 'milestones';

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

    public function user()
    {
    return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function job()
    {
    return $this->belongsTo('App\Models\Job', 'job_id', 'id');
    }
    public function bid()
    {
    return $this->belongsTo('App\Models\Bid', 'bid_id', 'id');
    }
    public function profile()
    {
    return $this->belongsTo('App\Models\Profile', 'profile_id', 'id')->with('media');
    }
}
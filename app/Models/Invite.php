<?php

namespace App\Models;

use App\Traits\Uuid;
use DigitalCloud\ModelNotes\HasNotes;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Invite extends Model implements HasMedia
{
    use HasMediaTrait, HasNotes, Uuid;

    /**
    * @var  string
    */
    protected $table = 'invites';

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];


    public function registerMediaCollections()
    {
        $this->addMediaCollection('project_files');

    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id')->with('media');
    }
    public function job()
    {
        return $this->belongsTo('App\Models\Job', 'job_id', 'id');
    }
}
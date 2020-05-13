<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Slug;


class Industry extends Model implements HasMedia
{
    use HasMediaTrait, SoftDeletes, Slug;

/**
* @var  string
*/
protected $table = 'industries';

protected $casts = [
'created_at' => 'datetime',
'updated_at' => 'datetime',
];

public function registerMediaCollections()
{
    $this->addMediaCollection('cover');

}

public function profiles()
{
    return $this->belongsToMany('App\Models\Profile');
}

public function jobs()
{
    return $this->hasMany('App\Models\Job');
}


}
<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{

/**
* @var  string
*/
protected $table = 'skills';


protected $fillable = ['title'];

protected $casts = [
'created_at' => 'datetime',
'updated_at' => 'datetime',
];

protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $model->slug = (string) Str::slug($model->title .'-'. time());
    });

    // static::updating(function ($model) {
    //     $model->slug = (string) Str::slug($model->title ."-". time());
    // });
}

public function profiles()
{
    return $this->belongsToMany('App\Models\Profile')->with('media');
}

public function jobs()
{
    return $this->belongsToMany('App\Models\Job');
}

}
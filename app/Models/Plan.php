<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{

    /**
    * @var  string
    */
    protected $table = 'plans';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'title', 'description', 'qauntity'
    ];

    public function user()
    {
        return $this->belongsToMany('App\Models\User')->withTimestamps()->withPivot('count');
    }

}
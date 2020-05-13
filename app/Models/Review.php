<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    /**
    * @var  string
    */
    protected $table = 'reviews';

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

    protected $fillable = ['user_id', 'body', 'reviewable_id', 'reviewable_type', 'rating'];


     /**
     * Get the owning reviewable model.
     */
    public function reviewable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }





}
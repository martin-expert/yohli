<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\Slug;
use DigitalCloud\ModelNotes\HasNotes;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


class BlogPost extends Model implements HasMedia
{
    use HasMediaTrait, HasNotes, Slug;

    /**
     * @var  string
     */
    protected $table = 'blog_posts';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('featured');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\BlogTag');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\BlogCategory');
    }
}

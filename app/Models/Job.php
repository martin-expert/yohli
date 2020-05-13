<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\Uuid;
use Illuminate\Support\Str;
use DigitalCloud\ModelNotes\HasNotes;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Job extends Model implements HasMedia
{
    use HasMediaTrait, HasNotes, Uuid;

    /**
     * @var  string
     */
    protected $table = 'jobs';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    protected $appends = [
        'rating',
        'reviewed'
    ];

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = (string) Str::slug($model->title .'-'. time());
            $model->uuid = Str::uuid();
        });

        // static::updating(function ($model) {
        //     $model->slug = (string) Str::slug($model->title ."-". time());
        // });
    }

    public function getReviewedAttribute()
    {
         if(is_null($this->ontime)){
             return false;
         }else {
            return true;
         }
    }


    public function getCreatedAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('project_files');
    }

    public function getRatingAttribute()
    {
        $rating = $this->reviews()->average('rating');

        return number_format($rating, 1);
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile')->with('media', 'reviews');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->with('profile');
    }

    public function industry()
    {
        return $this->belongsTo('App\Models\Industry', 'industry_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id', 'id');
    }
    public function job_budget()
    {
        return $this->belongsTo('App\Models\JobBudget', 'job_budget_id', 'id');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Models\Skill');
    }

    public function bids()
    {
        return $this->hasMany('App\Models\Bid');
    }

    public function accepted_bid()
    {
        return $this->hasOne('App\Models\Bid')->where('status', 'accepted');
    }

    /**
     * Get all of the job's reviews.
     */
    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'reviewable');
    }

    public function myReviews()
    {
        return $this->morphMany('App\Models\Review', 'reviewable')
        ->where('user_id', auth()->user()->id);
    }

    /**
     * Get all of the job's reviews.
     */
    public function payments()
    {
        return;
    }

    public function milestones()
    {
        return $this->hasMany('App\Models\Milestone');
    }
}

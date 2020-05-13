<?php

namespace App\Models;

use Illuminate\Support\Str;
use DigitalCloud\ModelNotes\HasNotes;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\Viewable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;

class Profile extends Model implements HasMedia, ViewableContract
{
    use HasMediaTrait, HasNotes, Viewable;
    /**
     * @var  string
     */
    protected $table = 'profiles';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'completion_rate', 
        'completion_time_rate', 
        'completion_budget_rate', 
        'rating', 'photo'
    ];

    protected $fillable = [
        'name', 'email', 'country_id', 'type'
    ];

       /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::orderedUuid();
        });

        static::updated(function ($model) {
            $user = User::find($model->user_id);
            if($user){
                $user->syncRoles([$model->type]); 
            }
            
        });

        static::deleting(function ($model) {
            if($model->jobs()->exists()){
                foreach ($model->jobs as $job) {
                    $job->profile_id = NULL;
                    $job->save();
                }
            }
            
        });

    }


    public function getCompletionRateAttribute()
    {
        $jobs_count = $this->jobs()->count();
        $jobs_completed = $this->jobs_completion()->count();

        if($jobs_count <= 0 ){
            return 0;
        }

        $rate = ($jobs_completed /  $jobs_count) * 100;

        return str_limit($rate , 4, '');

    }

    public function getCompletionTimeRateAttribute()
    {
        $jobs_count = $this->jobs()->count();
        $jobs_completed = $this->jobs_completion()->where('ontime', 'yes')->count();

        if($jobs_count <= 0 ){
            return 0;
        }

        $rate = ($jobs_completed /  $jobs_count) * 100;

        return str_limit($rate , 4, '');

    }

    public function getCompletionBudgetRateAttribute()
    {
        $jobs_count = $this->jobs()->count();
        $jobs_completed = $this->jobs_completion()->where('onbudget', 'yes')->count();

        if($jobs_count <= 0 ){
            return 0;
        }

        $rate = ($jobs_completed /  $jobs_count) * 100;

         return str_limit($rate , 4, '');
    }


    public function getRatingAttribute()
    {
        $rating = $this->reviews()->average('rating');

        return number_format($rating, 1);
    }

    public function getPhotoAttribute()
    {
        return $this->getFirstMediaUrl('profile', 'big');
    }
    
    public function registerMediaCollections()
    {
        $this->addMediaCollection('profile');
        $this->addMediaCollection('cv');
    }

    public function registerMediaConversions(Media $media = null)
    {
        // $this->addMediaConversion('thumb')
        //     ->width(100)
        //     ->height(100)->performOnCollections('profile');

        $this->addMediaConversion('thumb')
            ->crop('crop-center', 100, 100)
            ->performOnCollections('profile');

        $this->addMediaConversion('big')
        ->crop('crop-center', 300, 300)
        ->performOnCollections('profile');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }


    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Models\Skill');
    }

    public function social_links()
    {
        return $this->hasMany('App\Models\SocialLink');
    }

    public function bids()
    {
        return $this->hasMany('App\Models\Bid');
    }

    public function jobs()
    {
        return $this->hasMany('App\Models\Job');
    }

    public function jobs_completion()
    {
        return $this->jobs()->where('status', 'completed');
    }

    /**
     * Get all of the user's reviews.
     */
    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'reviewable');
    }
}

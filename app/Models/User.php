<?php

namespace App\Models;

use App\Traits\Messageable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use App\Traits\SetsParticipants;
use Laravel\Passport\HasApiTokens;
use DigitalCloud\ModelNotes\HasNotes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;



class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use Notifiable, Billable, HasRoles, HasApiTokens, HasNotes, AuditTrait, SetsParticipants, Messageable;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'profile_verified_at' => 'datetime',
    ];

     /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {

            $model->createAsStripeCustomer();

            $plan = Plan::where('plan_id', 'free')->first();
    
            $model->trial_ends_at = now()->addDays(30);

            Account::createWithAttributes(['name' => 'walet', 'user_id' => $model->id]);

            $model->plan()->sync([$plan->id => ['count' => 0]]);

        });

        static::updating(function ($model) {
            $profile = Profile::where('user_id', $model->id)->first();
            if($profile){
                if($model->review == 'successful'){
                    empty($model->profile_verified_at) ? $model->profile_verified_at = now()->copy()->toDateTimeString() : $model->profile_verified_at;
                    $profile->verified = 1;
                    $profile->save();
                }elseif($model->review == 'pending'){
                    $model->profile_verified_at = NULL;
                    $profile->verified = 0;
                    $profile->save();
                }
            }
            
        });


    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function getParticipantDetailsAttribute()
    {
        return [
            'id' => $this->id,
            'name' => "{$this->first_name} {$this->last_name}",
            'email' => $this->email,
            'profile' => $this->profile,

        ];
    }

    public function getParticipantDetails()
    {
        return [
            'name' => "{$this->first_name} {$this->last_name}",
            'email' => $this->email,
            'profile' => $this->profile,

        ];
    }
    

    public function getNameAttribute() 
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    public function getStripePlanAttribute() 
    {
        return $this->plan[0]->plan_id;
    }

    public function getRatingAttribute()
    {
        $rating = $this->reviews()->average('rating');

        return number_format($rating, 1);
    }


    public function profile()
    {
        return $this->hasOne('App\Models\Profile')->with('media', 'country');
    }

    public function jobs()
    {
        return $this->hasMany('App\Models\Job');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function getFullnameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function account()
    {
        return $this->hasOne('App\Models\Account');
    }

    public function plan()
    {
        return $this->belongsToMany('App\Models\Plan')->withTimestamps()->withPivot('count');
    }
}


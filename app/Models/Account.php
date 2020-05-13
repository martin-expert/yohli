<?php

namespace App\Models;

use App\Events\MoneyAdded;
use Illuminate\Support\Str;
use App\Events\AccountCreated;
use App\Events\AccountDeleted;
use App\Events\MoneySubtracted;
use DigitalCloud\ModelNotes\HasNotes;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasNotes;

    protected $guarded = [];

    public static function createWithAttributes(array $attributes): Account
    {
        $attributes['uuid'] = (string) Str::orderedUuid();
    
        /*
         * The account will be created inside this event using the generated uuid.
         */
        event(new AccountCreated($attributes));

        /*
         * The uuid will be used the retrieve the created account.
         */
        return static::uuid($attributes['uuid']);
    }

    public function getUsernameAttribute() 
    {
        return is_null($this->user) ? 'User Deleted' : $this->user->name;
    } 

    /*
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function uuid(string $uuid): ?Account
    {
        return static::where('uuid', $uuid)->first();
    }

    public function addMoney(int $amount)
    {
        event(new MoneyAdded($this->uuid, $amount,));
    }

    public function subtractMoney(int $amount)
    {
        event(new MoneySubtracted($amount));
    }

    public function remove()
    {
        event(new AccountDeleted($this->uuid));
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function transactions_count()
    {
        return $this->hasMany('App\Models\TransactionCount');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCount extends Model
{
    protected $table = 'transaction_counts';
    protected $guarded = [];

    public static function uuid(string $uuid)
    {
        return static::where('uuid', $uuid)->first();
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function incrementCount()
    {
        $this->count += 1;
        $this->save();
    }
    
}


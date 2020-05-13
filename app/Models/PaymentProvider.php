<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProvider extends Model
{

    /**
    * @var  string
    */
    protected $table = 'payment_providers';

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

    protected $guarded = [

    ];

}
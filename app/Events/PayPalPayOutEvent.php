<?php

namespace App\Events;


use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Queue\SerializesModels;

class PayPalPayOutEvent
{
    use SerializesModels;

    public $profile;

    public $transaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Profile $profile, Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->profile = $profile;
        
    }

}

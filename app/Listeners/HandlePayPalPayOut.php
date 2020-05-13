<?php

namespace App\Listeners;

use App\Services\PayPalService;
use App\Events\PayPalPayOutEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandlePayPalPayOut 
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PayPalPayOutEvent $event)
    {
        $paypalPayout = new PayPalService();
        $paypalPayout->singlePayout($event->profile, $event->transaction);
    }
}

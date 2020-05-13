<?php

namespace App\Listeners;

use App\Events\MoMoPayOutEvent;
use App\Services\PaymentService;

class HandleMoMoPayOut
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
    public function handle(MoMoPayOutEvent $event)
    {
        $momo = new PaymentService();
        $momo->floatToMobileMoney($event->profile, $event->transaction);
       
    }
}

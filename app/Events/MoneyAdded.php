<?php

namespace App\Events;

use Spatie\EventSourcing\ShouldBeStored;

class MoneyAdded implements ShouldBeStored
{

    /** @var int */
    public $amount;

    public function __construct($amount)
    {
        $this->amount = $amount;
    }
}

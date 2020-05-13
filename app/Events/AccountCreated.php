<?php

namespace App\Events;

use Spatie\EventSourcing\ShouldBeStored;

class AccountCreated implements ShouldBeStored
{
    /** @var array */
    public $accountAttributes;

    public function __construct(array $accountAttributes)
    {
        $this->accountAttributes = $accountAttributes;
    }
}

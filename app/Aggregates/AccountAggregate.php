<?php

namespace App\Aggregates;

use App\Models\Job;
use App\Models\Account;
use App\Events\MoneyAdded;
use App\Events\AccountCreated;
use App\Events\MoneySubtracted;
use Spatie\EventSourcing\AggregateRoot;
use App\Exceptions\CouldNotSubtractMoney;

final class AccountAggregate extends AggregateRoot
{
    /** @var int */
    private $balance = 0;

    /** @var int */
    private $accountLimit = 0;

    /** @var int */
    private $accountLimitHitCount = 0;

    public function setAccountLimit($limit)
    {
        $this->accountLimit = $limit;
    }

    // we need to add this method to count the amount of this the limit was hit
    public function applyAccountLimitHit()
    {
        $this->accountLimitHitCount++;
    }

    public function applyMoneyAdded(MoneyAdded $event)
    {
        $this->balance += $event->amount;
    }

    public function applyMoneySubtracted(MoneySubtracted $event)
    {
        $this->balance -= $event->amount;
    }
    public function createAccount(string $name, string $user_id)
    {
        $this->recordThat(new AccountCreated(['name' => $name, 'user_id' => $user_id]));
    }

    public function addMoney(int $amount)
    {
        $this->recordThat(new MoneyAdded($amount));
    }


    public function subtractMoney(int $amount)
    {
        if (!$this->hasSufficientFundsToSubtractAmount($amount)) {
            
            throw CouldNotSubtractMoney::notEnoughFunds($amount, $this->accountLimit);
        }

        $this->recordThat(new MoneySubtracted($amount));
    }

    private function hasSufficientFundsToSubtractAmount(int $amount): bool
    {
        return $this->balance - $amount >= $this->accountLimit;
    }
}

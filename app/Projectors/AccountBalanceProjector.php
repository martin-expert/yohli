<?php

namespace App\Projectors;

use App\Models\Account;
use App\Events\AccountCreated;
use App\Events\AccountDeleted;
use App\Events\MoneyAdded;
use App\Events\MoneySubtracted;
use Spatie\EventSourcing\Models\StoredEvent;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class AccountBalanceProjector implements Projector
{
    use ProjectsEvents;

    public function onAccountCreated(AccountCreated $event, string $aggregateUuid)
    {
        Account::create($event->accountAttributes);
    }
    public function onMoneyAdded(MoneyAdded $event, string $aggregateUuid)
    {
        $account = Account::uuid($aggregateUuid);
        $account->balance += $event->amount;
        $account->save();
    }
    public function onMoneySubtracted(MoneySubtracted $event, string $aggregateUuid)
    {
        $account = Account::uuid($aggregateUuid);
        $account->balance -= $event->amount;
        $account->save();
    }
    public function onAccountDeleted(AccountDeleted $event, string $aggregateUuid)
    {
        Account::uuid($aggregateUuid)->delete();
    }
}

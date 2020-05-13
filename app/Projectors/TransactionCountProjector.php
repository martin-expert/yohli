<?php

namespace App\Projectors;

use App\Models\Account;
use App\Events\MoneyAdded;
use App\Events\AccountCreated;
use App\Events\AccountDeleted;
use App\Events\MoneySubtracted;
use App\Models\TransactionCount;
use Spatie\EventSourcing\Models\StoredEvent;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class TransactionCountProjector implements Projector
{
        use ProjectsEvents;
    protected $handlesEvents = [
        AccountCreated::class => 'onAccountCreated',
        MoneyAdded::class => 'onMoneyAdded',
        MoneySubtracted::class => 'onMoneySubtracted',
        AccountDeleted::class => 'onAccountDeleted',
    ];
    public function onAccountCreated(AccountCreated $event, $uuid)
    {
        \Log::error($uuid);
        \Log::error(print_r($event, true));

        TransactionCount::create([
            'uuid' => $event->accountAttributes['uuid'],
            'user_id' => $event->accountAttributes['user_id'], 
            'account_id' =>  Account::where('user_id', $event->accountAttributes['user_id'])->first()->id, 
        ]);
    }
    public function onMoneyAdded(MoneyAdded $event, $aggregate_data)
    {
        TransactionCount::uuid($aggregate_data->aggregate_uuid)->incrementCount();
    }
    public function onMoneySubtracted(MoneySubtracted $event, $aggregate_data)
    {
        TransactionCount::uuid($aggregate_data->aggregate_uuid)->incrementCount();
    }
    public function onAccountDeleted(MoneyAdded $event,  $aggregate_data)
    {
        TransactionCount::uuid($aggregate_data->aggregate_uuid)->delete();
    }
}

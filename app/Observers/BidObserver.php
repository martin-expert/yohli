<?php

namespace App\Observers;

use App\Models\Bid;
use App\Models\Job;
use App\Models\User;
use App\Notifications\PendingBid;
use App\Notifications\BidAccepted;

class BidObserver
{
    /**
     * Handle the bid "created" event.
     *
     * @param  \App\Models\Bid  $bid
     * @return void
     */
    public function created(Bid $bid)
    {
        $user = $bid->job->owner;
        $job = $bid->job;
        if($user){
            $user->notify(new PendingBid($bid, $job));
        }

        if($bid->status == 'accepted'){
            $user = $bid->job->owner;
            $job = $bid->job;
            if($user){
                $user->notify(new PendingBid($bid, $job));
            }
        }

    }

    /**
     * Handle the bid "updated" event.
     *
     * @param  \App\Models\Bid  $bid
     * @return void
     */
    public function updated(Bid $bid)
    {
        if($bid->status == 'accepted'){
            $user = $bid->profile->user;
            $job = $bid->job;
            if($job){
                $user->notify(new BidAccepted($bid, $job));
            }
        }
    }

    /**
     * Handle the bid "deleted" event.
     *
     * @param  \App\Models\Bid  $bid
     * @return void
     */
    public function deleted(Bid $bid)
    {
        //
    }

    /**
     * Handle the bid "restored" event.
     *
     * @param  \App\Models\Bid  $bid
     * @return void
     */
    public function restored(Bid $bid)
    {
        //
    }

    /**
     * Handle the bid "force deleted" event.
     *
     * @param  \App\Models\Bid  $bid
     * @return void
     */
    public function forceDeleted(Bid $bid)
    {
        //
    }
}

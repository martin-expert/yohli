<?php

namespace App\Notifications;

use App\Models\Bid;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PendingBid extends Notification
{
    use Queueable;

    public $bid;

    public $job;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Bid $bid, Job $job)
    {
        $this->bid = $bid;
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $owner_name = $this->job->owner->name;
        $job_title = $this->job->title;
        $job_budget = $this->job->min_budget ==  $this->job->max_budget ?  $this->job->min_budget :  $this->job->min_budget. "-"  .$this->job->max_budget;
        $freelancer = $this->bid->profile->name;
        $rate = $this->bid->rate;

        return (new MailMessage)
                    ->subject('New Bid')
                    ->greeting("Hello $owner_name,")
                    ->line("You have a new bids for the job: $job_title; Job Budget($): $job_budget")
                    ->line("Freelancer: $freelancer and bid rate($) is $rate")
                    ->action('View Bids', url("/bidders/" . $this->job->uuid))
                    ->line('Thank you for using Yohli!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

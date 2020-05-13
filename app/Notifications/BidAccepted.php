<?php

namespace App\Notifications;

use App\Models\Bid;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BidAccepted extends Notification
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
        $job_budget = $this->job->min_budget ==  $this->job->max_budget ?  $this->job->min_budget :  $this->job->min_budget. " - "  .$this->job->max_budget;
        $freelancer = $this->bid->profile->name;
        $rate = $this->bid->rate;

        return (new MailMessage)
                    ->subject('Bid Accepted')
                    ->greeting("Hello $freelancer,")
                    ->line("Your Bid for the job: $job_title; Job Budget($): $job_budget has been accepted")
                    ->line("Hirer: $owner_name and bid rate($) $rate")
                    ->action('View Bids', url("/ongoing-jobs"))
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

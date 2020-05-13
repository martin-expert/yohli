<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PayPalPayOutProcessing extends Notification
{
    use Queueable;

        /**
     * Transaction Model.
     *
     * @return Illuminate\Eloquent\Model
     */
    public $transaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
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
        $account = $this->transaction->account()->with('user')->first();
       
        $status = '';
        if($this->transaction->description == "UNCLAIMED"){
            $paypalEmail = $account->user->profile->paypal;
            $status = "You do not have a Paypal account. A link to sign up for an account was sent to this $paypalEmail. However, if you fo not claim this withdrawal within 30 days, the funds will be returned to Yohli.";
        }
        return (new MailMessage)
                    ->error()
                    ->subject('Withdrawal Request Processing')
                    ->greeting('Hello! '. $account->user->name. ',')
                    ->line("Withdrawal Request Status: " . $this->transaction->description)
                    ->line('Amount $'.  $this->transaction->amount)            
                    ->line("You will get an email when request status changes. $status")
                    ->action('Transaction History', url('/transactions-history'))
                    ->line('Thank you for using '. config('app.name'));
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

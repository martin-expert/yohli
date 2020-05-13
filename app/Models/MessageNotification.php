<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageNotification extends Model
{
    use SoftDeletes;

    protected $table = 'message_notifications';
    protected $fillable = ['user_id', 'message_id', 'conversation_id'];
    protected $dates = ['deleted_at'];

    /**
     * Creates a new notification.
     *
     * @param Message      $message
     * @param Conversation $conversation
     */
    public static function make(Message $message, Conversation $conversation)
    {
        self::createCustomNotifications($message, $conversation);
    }

    public static function unReadNotifications(Model $participant)
    {
        return self::where([
            ['user_id', '=', $participant->getKey()],
            ['is_seen', '=', 0],
        ])->get();
    }

    public static function createCustomNotifications($message, $conversation)
    {
        $notification = [];

        foreach ($conversation->participants as $participation) {
            $is_sender = ($message->participation_id == $participation->id) ? 1 : 0;

            $notification[] = [
                'user_id'          => $participation->user_id,
                'message_id'       => $message->id,
                'participation_id' => $participation->id,
                'conversation_id'  => $conversation->id,
                'is_seen'          => $is_sender,
                'is_sender'        => $is_sender,
                'created_at'       => $message->created_at,
            ];
        }

        self::insert($notification);
    }

    public function markAsRead()
    {
        $this->is_seen = 1;
        $this->update(['is_seen' => 1]);
        $this->save();
    }
}

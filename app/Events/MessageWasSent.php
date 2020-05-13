<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageWasSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat-conversation.'.$this->message->conversation_id);
    }
    

    public function broadcastWith()
    {
        return [
            'message' => [
                'id'              => $this->message->getKey(),
                'body'            => $this->message->body,
                'attachments'     => $this->message->attachments,
                'conversation_id' => $this->message->conversation_id,
                'type'            => $this->message->type,
                'created_at'      => $this->message->created_at,
                'sender'          => $this->message->sender,
            ],
        ];
    }
}

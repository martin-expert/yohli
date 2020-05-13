<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Conversation;

class ConversationStarted 
{
    use InteractsWithSockets, Dispatchable, SerializesModels;
    /**
     * @var Conversation
     */
    public $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }
}

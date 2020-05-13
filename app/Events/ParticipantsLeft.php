<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ParticipantsLeft
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Conversation
     */
    public $conversation;
    public $participants;

    public function __construct(Conversation $conversation, $participants)
    {
        $this->conversation = $conversation;
        $this->participants = $participants;
    }
}
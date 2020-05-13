<?php

namespace App\Traits;

use App\Models\Conversation;
use App\Models\Participation;
use App\Models\MessageNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Messageable
{
    public function conversationsWithLastMessage()
    {
        return Conversation::wherein('id', $this->participation->pluck('conversation.id'))->with('participantsWithLastMessage')->get();
    }

    public function conversations()
    {
        return Conversation::wherein('id', $this->participation->pluck('conversation.id'))->latest()->with('participants', 'messages')->get();
    }


    public function chat_notifications()
    {
        $conversations = $this->conversations()->pluck('id');
        // \Log::error($conversations);
        $count = 0;
        foreach($conversations as $conversation){
            $notifications = MessageNotification::where('user_id', auth()->user()->id)
            ->where('conversation_id', $conversation)
            ->where('is_seen', 0)
            ->count();

            // \Log::error($notifications);


            $count = $count + $notifications;
        }

        return $count;


    }

    /**
     * @return HasMany
     */
    public function participation(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function joinConversation(Conversation $conversation)
    {
        $participation = new Participation([
            'user_id'   => $this->getKey(),
            'conversation_id'  => $conversation->getKey(),
        ]);

        $this->participation()->save($participation);
    }

    public function leaveConversation($conversationId)
    {
        $this->participation()->where([
            'user_id'   => $this->getKey(),
            'conversation_id'  => $conversationId,
        ])->delete();
    }
}
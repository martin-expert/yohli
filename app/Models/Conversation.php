<?php

namespace App\Models;

use App\Events\ParticipantsJoined;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{

    /**
    * @var  string
    */
    protected $table = 'conversations';

    protected $casts = [
        'data'           => 'array',
        'direct_message' => 'boolean',
        'private'        => 'boolean',
    ];

    protected $guarded = [

    ];


    // public function delete()
    // {
    //     if ($this->participants()->count()) {
    //         throw new DeletingConversationWithParticipantsException();
    //     }

    //     return parent::delete();
    // }

    public function getAuthUserAttribute()
    {
        return $this->participants->pluck('user');
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Conversation participants.
     *
     * @return HasMany
     */
    public function participants()
    {
        return $this->hasMany(Participation::class, 'conversation_id')->with('user', 'conversationWithLastMessage');
    }


     /**
     * Conversation participants.
     *
     * @return HasMany
     */
    public function participantsWithLastMessage()
    {
        return $this->hasMany(Participation::class, 'conversation_id')->with('user', 'conversationWithLastMessage');
    }

    public function getParticipants()
    {
        return $this->participants()->get()->pluck('user_id');
    }

    /**
     * Return the recent message in a Conversation.
     *
     * @return HasOne
     */
    public function last_message()
    {
        return $this->hasOne(Message::class)
            ->orderBy('messages.id', 'desc')
            ->with('participation');
    }

    /**
     * Messages in conversation.
     *
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id')->orderBy('messages.id', 'asc')->with('media');
    }

    /**
     * Get messages for a conversation.
     *
     * @param Model $participant
     * @param array $paginationParams
     * @param bool  $deleted
     *
     * @return LengthAwarePaginator|HasMany|Builder
     */
    public function getMessages(Model $participant, $paginationParams, $deleted = false)
    {
        return $this->getConversationMessages($participant, $paginationParams, $deleted);
    }

    public function getParticipantConversations($participant, array $options)
    {
        return $this->getConversationsList($participant, $options);
    }

    public function participantFromSender(Model $sender)
    {
        return $this->participants()->where([
            'conversation_id'  => $this->getKey(),
            'user_id'   => $sender->getKey(),
        ])->first();
    }

    /**
     * Add user to conversation.
     *
     * @param $participants
     *
     * @return Conversation
     */
    public function addParticipants(array $participants): self
    {
        foreach ($participants as $participant) {
            $participant->joinConversation($this);
        }

        event(new ParticipantsJoined($this, $participants));

        return $this;
    }

    /**
     * Remove participant from conversation.
     *
     * @param  $participants
     *
     * @return Conversation
     */
    public function removeParticipant($participants)
    {
        if (is_array($participants)) {
            foreach ($participants as $participant) {
                $participant->leaveConversation($this->getKey());
            }

            event(new ParticipantsLeft($this, $participants));

            return $this;
        }

        $participants->leaveConversation($this->getKey());

        event(new ParticipantsLeft($this, [$participants]));

        return $this;
    }

    /**
     * Starts a new conversation.
     *
     * @param array $payload
     *
     *
     * @return Conversation
     */
    public function start(array $payload): self
    {

        /** @var Conversation $conversation */
        $conversation = $this->create(['data' => $payload['data'], 'direct_message' => (bool) $payload['direct_message']]);

        if ($payload['participants']) {
            $conversation->addParticipants($payload['participants']);
        }

        return $conversation;
    }

      /**
     * Starts a new conversation.
     *
     * @param array $payload
     *
     *
     * @return Conversation
     */
    public static function new(array $payload): self
    {

        /** @var Conversation $conversation */
        $conversation = self::create(['data' => $payload['data'], 'direct_message' => (bool) $payload['direct_message'], 'job_id' => $payload['job_id']]);

        if ($payload['participants']) {
            $conversation->addParticipants($payload['participants']);
        }

        return $conversation;
    }

    /**
     * Sets conversation as public or private.
     *
     * @param bool $isPrivate
     *
     * @return Conversation
     */
    public function makePrivate($isPrivate = true)
    {
        $this->private = $isPrivate;
        $this->save();

        return $this;
    }

    /**
     * Sets conversation as direct message.
     *
     * @param bool $isDirect
     *
     *
     * @return Conversation
     */
    public function makeDirect($isDirect = true)
    {

        $participants = $this->participants()->get()->pluck('messageable');
        $this->direct_message = $isDirect;
        $this->save();

        return $this;
    }

  

    /**
     * Gets conversations for a specific participant.
     *
     * @param Model $participant
     * @param bool  $isDirectMessage
     *
     * @return Collection
     */
    public function participantConversations(Model $participant, bool $isDirectMessage = false): Collection
    {
        $conversations = $participant->participation->pluck('conversation');

        return $isDirectMessage ? $conversations->where('direct_message', 1) : $conversations;
    }

    /**
     * Get unread notifications.
     *
     * @param Model $participant
     *
     * @return Collection
     */
    public function unReadNotifications(Model $participant): Collection
    {
        $notifications = MessageNotification::where([
            ['user_id', '=', $participant->getKey()],
            ['conversation_id', '=', $this->id],
            ['is_seen', '=', 0],
        ])->get();

        return $notifications;
    }

    /**
     * Gets the notifications for the participant.
     *
     * @param  $participant
     * @param bool $readAll
     *
     * @return MessageNotification
     */
    public function getNotifications($participant, $readAll = false)
    {
        return $this->notifications($participant, $readAll);
    }


    /**
     * Marks all the messages in a conversation as read for the participant.
     *
     * @param Model $participant
     *
     * @return void
     */
    public function readAll(Model $participant): void
    {
        $this->getNotifications($participant, true);
    }

    /**
     * Get messages in conversation for the specific participant.
     *
     * @param Model $participant
     * @param $paginationParams
     * @param $deleted
     *
     * @return LengthAwarePaginator|HasMany|Builder
     */
    private function getConversationMessages(Model $participant, $paginationParams, $deleted)
    {
        $messages = $this->messages()
            ->join('message_notifications', 'message_notifications.message_id', '=', 'messages.id')
            ->where('message_notifications.user_id', $participant->getKey());
        $messages = $deleted ? $messages->whereNotNull('message_notifications.deleted_at') : $messages->whereNull('message_notifications.deleted_at');
        $messages = $messages->orderBy('messages.id', $paginationParams['sorting'])
            ->paginate(
                $paginationParams['perPage'],
                [
                    'message_notifications.updated_at as read_at',
                    'message_notifications.deleted_at as deleted_at',
                    'message_notifications.user_id',
                    'message_notifications.id as notification_id',
                    'message_notifications.is_seen',
                    'message_notifications.is_sender',
                    'messages.*',
                ],
                $paginationParams['pageName'],
                $paginationParams['page']
            );

        return $messages;
    }

    /**
     * @param Model $participant
     * @param $options
     *
     * @return mixed
     */
    private function getConversationsList(Model $participant, $options)
    {
        /** @var Builder $paginator */
        $paginator = $participant->participation()
            ->join('conversations as c', 'participation.conversation_id', '=', 'c.id')
            ->with([
                'conversation.last_message' => function ($query) use ($participant) {
                    $query->join('message_notifications', 'message_notifications.message_id', '=', 'messages.id')
                        ->select('message_notifications.*', 'messages.*')
                        ->where('message_notifications.user_id', $participant->getKey())
                        ->whereNull('message_notifications.deleted_at');
                },
                'conversation.participants.user',
            ])
            ->where('participation.user_id', $participant->getKey());

        if (isset($options['filters']['private'])) {
            $paginator = $paginator->where('c.private', (bool) $options['filters']['private']);
        }

        if (isset($options['filters']['direct_message'])) {
            $paginator = $paginator->where('c.direct_message', (bool) $options['filters']['direct_message']);
        }

        return $paginator
            ->orderBy('c.updated_at', 'DESC')
            ->orderBy('c.id', 'DESC')
            ->distinct('c.id')
            ->paginate($options['perPage'], ['participation.*', 'c.*'], $options['pageName'], $options['page']);
    }

    public function unDeletedCount()
    {
        return MessageNotification::where('conversation_id', $this->getKey())
            ->count();
    }

    private function notifications(Model $participant, $readAll)
    {
        $notifications = MessageNotification::where('user_id', $participant->getKey())
            ->where('conversation_id', $this->id);

        if ($readAll) {
            return $notifications->update(['is_seen' => 1]);
        }

        return $notifications->get();
    }

    private function clearConversation($participant): void
    {
        MessageNotification::where('user_id', $participant->getKey())
            ->where('conversation_id', $this->getKey())
            ->delete();
    }

    public function isDirectMessage(): bool
    {
        return (bool) $this->direct_message;
    }
}


<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Events\MessageWasSent;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Message extends Model implements HasMedia
{
    use HasMediaTrait;
    
    protected $fillable = [
        'body',
        'participation_id',
        'type',
    ];
    protected $table = 'messages';
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['conversation'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'flagged' => 'boolean',
    ];
    protected $appends = ['sender', 'media'];

    public function registerMediaCollections()
    {
        $this->addMediaCollection('attachments');
    }

    public function participation()
    {
        return $this->belongsTo(Participation::class, 'participation_id');
    }


    public function getSenderAttribute()
    {
        $participantModel = $this->participation->user;
        if (method_exists($participantModel, 'getParticipantDetails')) {
            return $participantModel->getParticipantDetails();
        }
        return $this->participation->user;
    }

    public function getMediaAttribute()
    {
        return $this->media()->get();
    }

    public function unreadCount(Model $participant)
    {
        return MessageNotification::where('user_id', $participant->getKey())
            ->where('is_seen', 0)
            ->count();
    }
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }
    // /**
    //  * Adds a message to a conversation.
    //  *
    //  * @param Conversation  $conversation
    //  * @param string        $body
    //  * @param Participation $participant
    //  * @param string        $type
    //  *
    //  * @return Model
    //  */
    // public function send(Conversation $conversation, string $body, Participation $participant, string $type = 'text'): Model
    // {
    //     $message = $conversation->messages()->create([
    //         'body'             => $body,
    //         'participation_id' => $participant->getKey(),
    //         'type'             => $type,
    //     ]);
        
    //     broadcast(new MessageWasSent($message))->toOthers();
    
    //     $this->createNotifications($message);
    //     return $message;
    // }

     /**
     * Adds a message to a conversation.
     *
     * @param Conversation  $conversation
     * @param string        $body
     * @param Participation $participant
     * @param string        $type
     *
     * @return Model
     */
    public static function send(Conversation $conversation, Request $request, Participation $participant, string $type = 'text'): Model
    {
        $message = $conversation->messages()->create([
            'body'             => $request->body ?? 'file',
            'participation_id' => $participant->getKey(),
            'type'             => $type,
        ]);

        if ($request->hasFile('files')) {
            $message
                ->addMultipleMediaFromRequest(['files'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('attachments');
                });
        }
        
        $message = self::find($message->id);
        
        broadcast(new MessageWasSent($message))->toOthers();
    
        self::createNotifications($message);
        return $message;
    }

    /**
     * Creates an entry in the message_notification table for each participant
     * This will be used to determine if a message is read or deleted.
     *
     * @param Message $message
     */
    protected static function createNotifications($message)
    {
        MessageNotification::make($message, $message->conversation);
    }
    /**
     * Deletes a message for the participant.
     *
     * @param Model $participant
     *
     * @return void
     */
    public function trash(Model $participant): void
    {
        MessageNotification::where('user_id', $participant->getKey())
            ->where('message_id', $this->getKey())
            ->delete();
        if ($this->unDeletedCount() === 0) {
            event(new AllParticipantsDeletedMessage($this));
        }
    }
    public function unDeletedCount()
    {
        return MessageNotification::where('message_id', $this->getKey())
            ->count();
    }
    /**
     * Return user notification for specific message.
     *
     * @param Model $participant
     *
     * @return MessageNotification
     */
    public function getNotification(Model $participant)
    {
        return MessageNotification::where('user_id', $participant->getKey())
            ->where('message_id', $this->id)
            ->select([
                '*',
                'updated_at as read_at',
            ])
            ->first();
            
    }
    /**
     * Marks message as read.
     *
     * @param $participant
     */
    public function markRead($participant): void
    {
        $this->getNotification($participant)->markAsRead();
    }
    public function flagged(Model $participant): bool
    {
        return (bool) MessageNotification::where('user_id', $participant->getKey())
            ->where('message_id', $this->id)
            ->where('flagged', 1)
            ->first();
    }
    public function toggleFlag(Model $participant): self
    {
        MessageNotification::where('user_id', $participant->getKey())
            ->where('message_id', $this->id)
            ->update(['flagged' => $this->flagged($participant) ? false : true]);
        return $this;
    }
}
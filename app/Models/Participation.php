<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Participation extends Model
{
//    use SoftDeletes;
    protected $table = 'participation';
    protected $fillable = [
        'conversation_id',
        'settings',
    ];
    protected $casts = [
        'settings' => 'array',
    ];
    /**
     * Conversation.
     *
     * @return BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function conversationWithMessages()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id')->with('messages');
    }

    public function conversationWithLastMessage()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id')->with('last_message');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('profile');
    }

}
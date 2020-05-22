<?php

namespace App\Console\Commands;
use App\Events\MessageWasSent;

use Illuminate\Console\Command;

class SendChatMessage extends Command
{
    protected $signature = 'chat:message {message}';

    protected $description = 'Send chat message.';

    public function handle()
    {
        // Fire off an event, just randomly grabbing the first user for now
        $message = \App\Models\Message::create([
            'body' => $this->argument('message'),
            'participation_id' => 1,
            'conversation_id' => 12,
            'type'=> 'text',
        ]);

        event(new MessageWasSent($message));
    }
}

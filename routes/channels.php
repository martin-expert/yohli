<?php

use App\Models\Participation;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat-conversation.{id}', function ($user, $id) {
    $participation = Participation::where('user_id', $user->id)->where('conversation_id', $id)->first();
    if($participation){
      return ['id' => $user->id, 'name' => $user->name];
    }
});

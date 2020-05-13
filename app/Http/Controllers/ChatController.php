<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Models\Participation;
use App\Models\MessageNotification;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
         
         $conversations = Auth::user()->conversations();
         $conversations = $conversations->map(function($item, $value){
             return [
                 $item,
                // 'auth_user' => $item->participants->filter(function($innerItem){
                //     return $innerItem->user->id == Auth::user()->id;
                // })
             ];
                
         })->values()->flatten();

        //   return ($conversations);

        $timezone = geoip($request->ip());
        $timezone = $timezone['timezone'];

        // dd($timezone);

         return view('dashboard.messages', compact('conversations', 'timezone'));
    }

    public function index_json(Request $request)
    {
         
         $conversations = Auth::user()->conversations();
         $conversations = $conversations->map(function($item, $value){
             return [
                 $item,
                // 'auth_user' => $item->participants->filter(function($innerItem){
                //     return $innerItem->user->id == Auth::user()->id;
                // })
             ];
                
         })->values()->flatten();

        // return ($conversations);

        // dd($timezone);

         return response()->json($conversations);
    }


    public function send(Request $request, $id)
    {

        $conversation = Conversation::Find($id);

        $participant = Participation::where('conversation_id', $conversation->id)
        ->where('user_id', $request->user()->id)
        ->first();

        $message = Message::send($conversation,  $request, $participant);

        // $newMessage = Message::find($message->id);

        $notification = MessageNotification::where('user_id', $request->user()->id)
        ->where('conversation_id', $conversation->id)
        ->update([
            'is_seen' => 1
        ]);

        $timezone = geoip($request->ip());
        $timezone = $timezone['timezone'];

        $message =[
            'id'              => $message->getKey(),
            'body'            => $message->body,
            'attachments'     => $message->attachments,
            'conversation_id' => $message->conversation_id,
            'type'            => $message->type,
            'created_at'      => $message->created_at->timezone($timezone)->diffForHumans(),
            'sender'          => $message->sender,
        ];
        return response()->json($message);
    }

    public function markSeen(Request $request)
    {
        $notification = MessageNotification::where('user_id', $request->user()->id)
        ->where('conversation_id', $request->id)
        ->update([
            'is_seen' => 1
        ]);
    }



}

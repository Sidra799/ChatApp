<?php

namespace App\Http\Controllers;

use App\Events\joinChannel;
use App\Events\MessageSent;
use App\Message;
use App\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('chat');
    }
    public function fetchMessages()
    {
        return Message::with('user')->get();
    }
    public function sendMessages(Request $request)
    {
        $userId = $request->get('userId');
        $message = auth()->user()->messages()->create([
            'message' => $request->get('message')
        ]);
        broadcast(new MessageSent($userId,$message->load('user')))->toOthers();
        return ['status' => "success"];
    }
    public function showContact()
    {
        return view('contact');
    }
    public function joinUser(Request $request)
    {
        $userId = $request->get('userId');
        // dd($userId);
        // $message = auth()->user()->messages()->create([
        //     'message' => "Hello"
        // ]);

        $response  = broadcast(new JoinChannel($userId,"Hello" ));
        return ['status' => $userId];
    }
}

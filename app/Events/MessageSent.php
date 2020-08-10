<?php

namespace App\Events;

use App\Message;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PresenceChannel as ChannelsPresenceChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $userId, $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, Message $message)
    {
        $this->message= $message;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('App.User.'.$this->userId);
    }
}

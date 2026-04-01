<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chatroom;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class LivewireChatroom extends Component
{
    public Chatroom $chatroom;
    public $messages = [];
    public $body = '';

    protected $listeners = ['echo-private:chatroom.{chatroom.id}, MessageSent' => 'messageReceived',];

    public function mount(Chatroom $chatroom) {
        // set current chatroom to the current one being displayed
        $this->chatroom = $chatroom;

        // get current messages that belong to said chatroom
        $this->messages = Message::with('user')
                        ->where('chatroom_id', $chatroom->id)
                        ->latest()
                        ->take(50)
                        ->get()
                        ->reverse()
                        ->map(fn($m) => (object) $m->toArray())
                        ->toArray();
    }

    public function sendMessage() {
        // validate incoming message is a string (no surprises)
        $this->validate([
            'body'=>'required|string|max:500',
        ]);

        // create the new message in the db
        Message::create([
            'chatroom_id' => $this->chatroom->id,
            'user_id' => Auth::id(),
            'body' => $this->body,
        ]);

        // set the body back to being empty
        $this->body = '';
    }

    public function messageReceived($payload) {
        $this->messages->push((object) $payload);
    }

    public function render()
    {
        return view('livewire.chatroom');
    }
}

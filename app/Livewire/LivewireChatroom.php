<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chatroom;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class LivewireChatroom extends Component
{
    public Chatroom $chatroom;
    public $body = '';
    
    protected function getListeners(): array
    {
        return [
            "echo-private:chatroom.{$this->chatroom->id},.message-sent" => '$refresh',
        ];
    }

    public function mount(Chatroom $chatroom) {
        // set current chatroom to the current one being displayed
        $this->chatroom = $chatroom;
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

    public function messageReceived() {
        // refresh
    }

    public function render()
    {
        $messages = Message::with('user')
                        ->where('chatroom_id', $this->chatroom->id)
                        ->latest()
                        ->take(50)
                        ->get()
                        ->reverse();

        return view('livewire.chatroom', ['messages'=>$messages]);
    }
}

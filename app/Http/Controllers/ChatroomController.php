<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Module;

class ChatroomController extends Controller
{
    public function show(Module $module) {
        Gate::authorize('view', $module);

        $chatroom = $module->chatroom;

        if(!$chatroom) {
            abort(404, 'Chatroom not found!');
        }
        
        return view('modules.chatroom', ['module'=>$module, 'chatroom'=>$chatroom]);
    }
}

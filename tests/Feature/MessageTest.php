<?php

use App\Models\User;
use App\Models\Message;
use App\Models\Module;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

beforeEach(function() {
    DB::table('roles')->insert([
        ['id'=>1, 'name'=>'Student'],
        ['id'=>2, 'name'=>'Teaching Assistant'],
        ['id'=>3, 'name'=>'Lecturer'],
        ['id'=>4, 'name'=>'Admin'],
    ]);
});

test('my messages return me', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);
    $module = Module::factory()->create();

    $message = Message::factory()->create([
        'user_id' => $user->id,
        'chatroom_id' => $module->chatroom->id,
    ]);

    expect($message->getDisplayName($user))->toBe('Me');
});

test('anonymous when two students', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $sender = User::factory()->create();
    $sender->roles()->attach(1);

    $module = Module::factory()->create();

    $message = Message::factory()->create([
        'user_id' => $sender->id,
        'chatroom_id' => $module->chatroom->id,
    ]);

    expect($message->getDisplayName($user))->toBe('Anonymous Student');
});

test('real name when staff is viewer', function () {
    $user = User::factory()->create();
    $user->roles()->attach(2);

    $sender = User::factory()->create();
    $sender->roles()->attach(1);

    $module = Module::factory()->create();

    $message = Message::factory()->create([
        'user_id' => $sender->id,
        'chatroom_id' => $module->chatroom->id,
    ]);

    expect($message->getDisplayName($user))->toBe($sender->name);
});


test('real name when staff is sender', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $sender = User::factory()->create();
    $sender->roles()->attach(2);

    $module = Module::factory()->create();

    $message = Message::factory()->create([
        'user_id' => $sender->id,
        'chatroom_id' => $module->chatroom->id,
    ]);

    expect($message->getDisplayName($user))->toBe($sender->name);
});
<?php

use App\Models\Chatroom;
use App\Models\Message;
use App\Models\User;
use App\Models\Module;
use App\Livewire\LivewireChatroom;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs};
use function Pest\Laravel\{assertDatabaseHas};
use function Pest\Laravel\{assertDatabaseMissing};
use function Pest\Laravel\{get};


use Illuminate\Support\Facades\DB;

beforeEach(function() {
    DB::table('roles')->insert([
        ['id'=>1, 'name'=>'Student'],
        ['id'=>2, 'name'=>'Teaching Assistant'],
        ['id'=>3, 'name'=>'Lecturer'],
        ['id'=>4, 'name'=>'Admin'],
    ]);
});

test('students can send messages', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    $this->actingAs($user);

    Livewire::test(LivewireChatroom::class, ['chatroom' => $module->chatroom])
    ->set('body', 'hello guys')
    ->call('sendMessage');

    assertDatabaseHas('messages', [
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user->id,
        'body' => 'hello guys',
    ]);
});

test('body made empty after sending', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    $this->actingAs($user);

    Livewire::test(LivewireChatroom::class, ['chatroom' => $module->chatroom])
    ->set('body', 'hello guys')
    ->call('sendMessage')
    ->assertSet('body', '');
});

test('empty message not sent', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    $this->actingAs($user);

    Livewire::test(LivewireChatroom::class, ['chatroom' => $module->chatroom])
    ->set('body', '')
    ->call('sendMessage')
    ->assertHasErrors(['body'=>'required']);

    assertDatabaseMissing('messages', [
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user->id,
    ]);
});

test('over 500 chars not send', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    $this->actingAs($user);

    Livewire::test(LivewireChatroom::class, ['chatroom' => $module->chatroom])
    ->set('body', str_repeat('hi',251))
    ->call('sendMessage')
    ->assertHasErrors(['body'=>'max']);

    assertDatabaseMissing('messages', [
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user->id,
    ]);
});

test('students can see messages', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    Message::factory()->create([
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user->id,
        'body' => 'Test',
    ]);
    $this->actingAs($user);

    Livewire::test(LivewireChatroom::class, ['chatroom' => $module->chatroom])
    ->assertSee('Test');
});

test('50 latest messages displayed', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    Message::factory(55)->create([
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    $component = Livewire::test(LivewireChatroom::class, ['chatroom' => $module->chatroom]);
    expect($component->viewData('messages')->count())->toBe(50);
});

// ChatroomController Tests, done in here as short and related

test('enroled users can see chatroom', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    actingAs($user)
    ->get(route('chatroom.show', $module))
    ->assertOk();
});

test('unenroled users cant see chatroom', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create();
    $module->chatroom->update(['is_open' => true]);

    actingAs($user)
    ->get(route('chatroom.show', $module))
    ->assertForbidden();
});

test('guests cant see chatroom', function () {
    $module = Module::factory()->create();
    $module->chatroom->update(['is_open' => true]);

    get(route('chatroom.show', $module))
    ->assertRedirect('login');
});
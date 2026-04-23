<?php
use App\Models\User;
use App\Models\Module;
use App\Models\Chatroom;
use App\Models\Message;

use function Pest\Laravel\{actingAs};

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

beforeEach(function() {
    DB::table('roles')->insert([
        ['id'=>1, 'name'=>'Student'],
        ['id'=>2, 'name'=>'Teaching Assistant'],
        ['id'=>3, 'name'=>'Lecturer'],
        ['id'=>4, 'name'=>'Admin'],
    ]);
});

test('lecturer can access analytics', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    actingAs($user)
    ->get(route('chatroom.analytics', $module))
    ->assertOk();
});

test('ta can access analytics', function () {
    $user = User::factory()->create();
    $user->roles()->attach(2);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    actingAs($user)
    ->get(route('chatroom.analytics', $module))
    ->assertOk();
});

test('admin can access analytics', function () {
    $user = User::factory()->create();
    $user->roles()->attach(4);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    actingAs($user)
    ->get(route('chatroom.analytics', $module))
    ->assertOk();
});

test('student cant access analytics', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    actingAs($user)
    ->get(route('chatroom.analytics', $module))
    ->assertForbidden();
});

test('total messages is correct', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    Message::factory()->count(3)->create([
        'chatroom_id' => $module->chatroom->id,
    ]);

    actingAs($user)
    ->get(route('chatroom.analytics', $module))
    ->assertOk()
    ->assertSee("3");
});

test('unqique senders is correct', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $user2 = User::factory()->create();
    $user2->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach([$user->id, $user2->id]);
    $module->chatroom->update(['is_open' => true]);

    Message::factory()->count(3)->create([
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user->id,
    ]);

    Message::factory()->create([
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user2->id,
    ]);

    actingAs($user)
    ->get(route('chatroom.analytics', $module))
    ->assertOk()
    ->assertSee("2");
});

test('student participation is correct', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $user2 = User::factory()->create();
    $user2->roles()->attach(1);

    $user3 = User::factory()->create();
    $user3->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach([$user->id, $user2->id, $user3->id]);
    $module->chatroom->update(['is_open' => true]);

    Message::factory()->count(3)->create([
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user->id,
    ]);

    actingAs($user)
    ->get(route('chatroom.analytics', $module))
    ->assertOk()
    ->assertSee("50");
});

test('zero participation is correct', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $user2 = User::factory()->create();
    $user2->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach([$user->id, $user2->id]);
    $module->chatroom->update(['is_open' => true]);

    Message::factory()->count(3)->create([
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user->id,
    ]);

    actingAs($user)
    ->get(route('chatroom.analytics', $module))
    ->assertOk()
    ->assertSee("0");
});

test('messages are grouped into time intervals', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->chatroom->update(['is_open' => true]);

    $now = Carbon::now()->floorMinutes(10);

    Message::factory()->count(3)->create([
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user->id,
        'created_at'=> $now,
    ]);

    Message::factory()->count(3)->create([
        'chatroom_id' => $module->chatroom->id,
        'user_id' => $user->id,
        'created_at'=> $now->copy()->subMinutes(10),
    ]);

    actingAs($user)
    ->get(route('chatroom.analytics', $module))
    ->assertOk()
    ->assertSee($now->format('H:i'))
    ->assertSee($now->copy()->subMinutes(10)->format('H:i'));
});
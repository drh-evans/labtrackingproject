<?php
use App\Models\User;
use App\Models\Module;
use App\Models\Queue;
use App\Models\QueueEntry;

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
    $module->queue->update(['is_open' => true]);

    actingAs($user)
    ->get(route('queue.analytics', $module))
    ->assertOk();
});

test('ta can access analytics', function () {
    $user = User::factory()->create();
    $user->roles()->attach(2);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    actingAs($user)
    ->get(route('queue.analytics', $module))
    ->assertOk();
});

test('admin can access analytics', function () {
    $user = User::factory()->create();
    $user->roles()->attach(4);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    actingAs($user)
    ->get(route('queue.analytics', $module))
    ->assertOk();
});

test('student cant access analytics', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    actingAs($user)
    ->get(route('queue.analytics', $module))
    ->assertForbidden();
});

test('0 seconds shown when no left entries', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    actingAs($user)
    ->get(route('queue.analytics', $module))
    ->assertOk()
    ->assertSee("00:00");
});

test('average wait calculated correctly', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => null,
        'joined_at' => Carbon::now()->subMinutes(4),
        'left_at'=> Carbon::now(),
    ]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => null,
        'joined_at' => Carbon::now()->subMinutes(6),
        'left_at'=> Carbon::now(),
    ]);

    // average of 4+6/2 is 5
    actingAs($user)
    ->get(route('queue.analytics', $module))
    ->assertOk()
    ->assertSee("05:00");
});

test('longest wait is correct', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => null,
        'joined_at' => Carbon::now()->subMinutes(4),
        'left_at'=> Carbon::now(),
    ]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => null,
        'joined_at' => Carbon::now()->subMinutes(10),
        'left_at'=> Carbon::now(),
    ]);

    actingAs($user)
    ->get(route('queue.analytics', $module))
    ->assertOk()
    ->assertSee("10:00");
});

test('total students helped is correct', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => null,
        'joined_at' => Carbon::now()->subMinutes(4),
        'left_at'=> Carbon::now(),
    ]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => null,
        'joined_at' => Carbon::now()->subMinutes(6),
        'left_at'=> null,
    ]);

    actingAs($user)
    ->get(route('queue.analytics', $module))
    ->assertOk()
    ->assertSee("1");
});

test('assistance type correctly shown', function () {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => 1,
        'assistance_type' => 'Sign-off',
        'joined_at' => Carbon::now()->subMinutes(4),
        'left_at'=> null,
    ]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => 2,
        'assistance_type' => 'Assistance',
        'joined_at' => Carbon::now()->subMinutes(6),
        'left_at'=> null,
    ]);

    // average of 4+6/2 is 5
    actingAs($user)
    ->get(route('queue.analytics', $module))
    ->assertOk()
    ->assertSee("Assistance")
    ->assertSee("Sign-off");
});


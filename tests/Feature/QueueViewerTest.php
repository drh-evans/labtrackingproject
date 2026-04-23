<?php

use App\Models\User;
use App\Models\Module;
use App\Models\Queue;
use App\Models\QueueEntry;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use App\Livewire\QueueViewer;

use function Pest\Laravel\{actingAs};
use function Pest\Laravel\{assertDatabaseHas};
use function Pest\Laravel\{assertDatabaseMissing};
use function Pest\Laravel\{assertDatabaseCount};


beforeEach(function() {
    DB::table('roles')->insert([
        ['id'=>1, 'name'=>'Student'],
        ['id'=>2, 'name'=>'Teaching Assistant'],
        ['id'=>3, 'name'=>'Lecturer'],
        ['id'=>4, 'name'=>'Admin'],
    ]);
});

test('view current queue entries', function() {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    QueueEntry::factory()->create([
        'student_id' => $user->id,
        'queue_id' => $module->queue->id,
        'queue_position' => 1,
    ]);

    actingAs($user);

    Livewire::test(QueueViewer::class, ['queue' => $module->queue])
    ->assertSee($user->name);
});

test('departed queue entries not shown', function() {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($user->id);
    $module->queue->update(['is_open' => true]);

    QueueEntry::factory()->create([
        'student_id' => $user->id,
        'queue_id' => $module->queue->id,
        'queue_position' => null,
        'left_at' => now(),
    ]);

    actingAs($user);

    Livewire::test(QueueViewer::class, ['queue' => $module->queue])
    ->assertDontSee($user->name);
});
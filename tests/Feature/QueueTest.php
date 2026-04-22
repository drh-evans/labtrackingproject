<?php
use App\Models\User;
use App\Models\Module;
use App\Models\Queue;
use App\Models\QueueEntry;

use Illuminate\Support\Facades\DB;

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

// basic queue functionality
test('student can join open queue', function () {
    $student = User::factory()->create();
    $student->roles()->attach(1);

    $module = Module::factory()->create();
    $module->queue->update(['is_open'=>true]);
    $module->users()->attach($student->id);

    actingAs($student)->post(route('queue.join', $module), [
        'task' => 'Task 1',
        'location' => 'CoFo 104, A11',
        'assistanceType' => 'Assistance',
        'information' => 'Dont understand djikstras',
    ])->assertRedirect();

    assertDatabaseHas('queue_entries', [
        'student_id' => $student->id,
        'queue_id' => $module->queue->id,
    ]);
});

test('student cannot join closed queue', function () {
    $student = User::factory()->create();
    $student->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($student->id);

    actingAs($student)->post(route('queue.join', $module), [
        'task' => 'Task 1',
        'location' => 'CoFo 104, A11',
        'assistanceType' => 'Assistance',
        'information' => 'Dont understand djikstras',
    ])->assertForbidden();
});

test('ta cannot join open queue', function () {
    $ta = User::factory()->create();
    $ta->roles()->attach(2);

    $module = Module::factory()->create();
    $module->queue->update(['is_open'=>true]);
    $module->users()->attach($ta->id);

    actingAs($ta)->post(route('queue.join', $module), [
        'task' => 'Task 1',
        'location' => 'CoFo 104, A11',
        'assistanceType' => 'Assistance',
        'information' => 'Dont understand djikstras',
    ])->assertForbidden();
});

test('lecturer cannot join open queue', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $module = Module::factory()->create();
    $module->queue->update(['is_open'=>true]);
    $module->users()->attach($lecturer->id);

    actingAs($lecturer)->post(route('queue.join', $module), [
        'task' => 'Task 1',
        'location' => 'CoFo 104, A11',
        'assistanceType' => 'Assistance',
        'information' => 'Dont understand djikstras',
    ])->assertForbidden();
});

test('student cant join queue twice', function () {
    $student = User::factory()->create();
    $student->roles()->attach(1);

    $module = Module::factory()->create();
    $module->queue->update(['is_open'=>true]);
    $module->users()->attach($student->id);

    $data = [
        'task' => 'Task 1',
        'location' => 'CoFo 104, A11',
        'assistanceType' => 'Assistance',
        'information' => 'Dont understand djikstras',
    ];

    actingAs($student)->post(route('queue.join', $module), $data)->assertRedirect();
    actingAs($student)->post(route('queue.join', $module), $data)->assertRedirect();

    assertDatabaseCount('queue_entries', 1);
});

// queue reordering functionality

test('queue reorders after leaving', function () {
    $module = Module::factory()->create();
    $module->queue->update(['is_open'=>true]);

    $entry1 = QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => 1,
    ]);

    $entry2 = QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => 2,
    ]);

    $entry3 = QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => 3,
    ]);

    $entry1->update(['left_at'=>now(), 'queue_position' => null]);
    $module->queue->reorderPositions();

    assertDatabaseHas('queue_entries', ['id'=> $entry2->id, 'queue_position'=> 1]);
    assertDatabaseHas('queue_entries', ['id'=> $entry3->id, 'queue_position'=> 2]);
});

test('queue position is null after leaving', function () {
    $module = Module::factory()->create();
    $module->queue->update(['is_open'=>true]);

    $entry = QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => 1,
    ]);

    $entry->update(['left_at'=>now(), 'queue_position' => null]);
    $module->queue->reorderPositions();

    assertDatabaseHas('queue_entries', ['id'=> $entry->id, 'queue_position'=> null]);
    assertDatabaseMissing('queue_entries', ['id'=> $entry->id, 'left_at'=> null]);
});
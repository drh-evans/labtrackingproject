<?php
use function Pest\Laravel\{actingAs};
use function Pest\Laravel\{assertDatabaseHas};
use function Pest\Laravel\{assertDatabaseMissing};
use function Pest\Laravel\{assertDatabaseCount};

use App\Models\User;
use App\Models\Module;
use App\Models\LabTask;
use App\Models\QueueEntry;
use App\Livewire\ToggleQueueButton;
use Livewire\Livewire;
use Illuminate\Support\Facades\DB;

beforeEach(function() {
    DB::table('roles')->insert([
        ['id'=>1, 'name'=>'Student'],
        ['id'=>2, 'name'=>'Teaching Assistant'],
        ['id'=>3, 'name'=>'Lecturer'],
        ['id'=>4, 'name'=>'Admin'],
    ]);
});

test('student can see their module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);

    actingAs($user)
    ->get(route('modules.show', $module))
    ->assertOk();
});

test('student cant view module they are not in', function() {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);

    actingAs($user)
    ->get(route('modules.show', $module))
    ->assertForbidden();
});

test('ta can see their module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(2);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);

    actingAs($user)
    ->get(route('modules.show', $module))
    ->assertOk();
});

test('ta cant view module they are not in', function() {
    $user = User::factory()->create();
    $user->roles()->attach(2);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);

    actingAs($user)
    ->get(route('modules.show', $module))
    ->assertForbidden();
});


test('lecturer can see their module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);
    $module->users()->attach($user->id);

    actingAs($user)
    ->get(route('modules.show', $module))
    ->assertOk();
});

test('lecturer cant view module they are not in', function() {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);

    actingAs($user)
    ->get(route('modules.show', $module))
    ->assertForbidden();
});


test('student cant create a module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    actingAs($user)
    ->get(route('modules.create'))
    ->assertForbidden();
});

test('ta cant create a module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(2);

    actingAs($user)
    ->get(route('modules.create'))
    ->assertForbidden();
});

test('lecturer cant create a module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    actingAs($user)
    ->get(route('modules.create'))
    ->assertForbidden();
});

test('Admin can create a module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(4);

    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    actingAs($user)->post(route('modules.store', [
        'module_code' => 'CS101',
        'module_name' => 'Introductory Test',
        'module_leader_id' => $lecturer->id,
        'year_group' => 1,
        'year_taught' => 2026,
        'bg_image_url' => null,
    ]))->assertRedirect(route('admin.dashboard'));

    assertDatabaseHas('modules', [
        'module_code' => 'CS101',
        'year_taught' => 2026,
    ]);
});

test('admin can delete a module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(4);

    $module = Module::factory()->create();

    actingAs($user)
    ->delete(route('modules.destroy', $module))
    ->assertRedirect(route('modules.index'));

    assertDatabaseMissing('modules', ['id' => $module->id]);
});

test('student cant delete a module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);

    actingAs($user)
    ->delete(route('modules.destroy', $module))
    ->assertForbidden();
});

test('ta cant delete a module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(2);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);

    actingAs($user)
    ->delete(route('modules.destroy', $module))
    ->assertForbidden();
});

test('lecturers cant delete a module', function() {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $user->id]);

    actingAs($user)
    ->delete(route('modules.destroy', $module))
    ->assertForbidden();
});

test('chatroom and queue made on module creation', function() {
    $user = User::factory()->create();
    $user->roles()->attach(4);

    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    actingAs($user)->post(route('modules.store', [
        'module_code' => 'CS101',
        'module_name' => 'Introductory Test',
        'module_leader_id' => $lecturer->id,
        'year_group' => 1,
        'year_taught' => 2026,
        'bg_image_url' => null,
    ]));

    $module = Module::where('module_code', 'CS101')->first();

    assertDatabaseHas('queues', [
        'module_id' => $module->id,
    ]);

    assertDatabaseHas('chatrooms', [
        'module_id' => $module->id,
    ]);
});


// opening labs test
test('lecturer can open lab', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach($lecturer->id);

    actingAs($lecturer);

    Livewire::test(ToggleQueueButton::class, ['module'=>$module])
    ->call('toggleQueue');
    assertDatabaseHas('queues', [
        'module_id'=>$module->id, 
        'is_open'=>true,
        ]);
});

test('lecturer can close lab', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach($lecturer->id);
    $module->queue->update(['is_open' => true]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => 1,
    ]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => 2,
    ]);

    QueueEntry::factory()->create([
        'queue_id' => $module->queue->id,
        'queue_position' => 3,
    ]);

    actingAs($lecturer);

    Livewire::test(ToggleQueueButton::class, ['module'=>$module])
    ->call('toggleQueue');
    assertDatabaseCount('queue_entries', 0);
});

test('student cant open lab', function () {
    $student = User::factory()->create();
    $student->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $student->id]);
    $module->users()->attach($student->id);

    actingAs($student);

    Livewire::test(ToggleQueueButton::class, ['module'=>$module])
    ->call('toggleQueue')->assertForbidden();
});

test('ta cant open lab', function () {
    $ta = User::factory()->create();
    $ta->roles()->attach(2);

    $module = Module::factory()->create(['module_leader_id' => $ta->id]);
    $module->users()->attach($ta->id);

    actingAs($ta);

    Livewire::test(ToggleQueueButton::class, ['module'=>$module])
    ->call('toggleQueue')->assertForbidden();
});

<?php
use App\Models\User;
use App\Models\Module;
use App\Models\LabTask;

use function Pest\Laravel\{actingAs};
use function Pest\Laravel\{assertDatabaseHas};
use function Pest\Laravel\{assertDatabaseMissing};

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

test('lecturer can create task', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach($lecturer->id);

    actingAs($lecturer)->post(route('lab-task.store', $module), [
        'task_name' => 'Task 1',
        'description' => 'Test Task',
        'total_points' => 10,
        'fm_due_date' => Carbon::now()->addDays(7),
        'hm_due_date' => Carbon::now()->addDays(14),
    ])->assertRedirect(route('modules.show', $module->id));

    assertDatabaseHas('lab_tasks', [
        'module_id' => $module->id,
        'task_name' => 'Task 1',
    ]);
});

test('student cant create task', function () {
    $student = User::factory()->create();
    $student->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $student->id]);
    $module->users()->attach($student->id);

    actingAs($student)->post(route('lab-task.store', $module), [
        'task_name' => 'Task 1',
        'description' => 'Test Task',
        'total_points' => 10,
        'fm_due_date' => Carbon::now()->addDays(7),
        'hm_due_date' => Carbon::now()->addDays(14),
    ])->assertForbidden();

    assertDatabaseMissing('lab_tasks', [
        'task_name' => 'Task 1',
    ]);
});

test('lecturer can edit task', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach($lecturer->id);

    $labTask = LabTask::factory()->create(['module_id' => $module->id]);

    actingAs($lecturer)->patch(route('lab-task.update', [$module, $labTask]), [
        'task_name' => 'Task 1.5',
        'description' => 'Test Task',
        'total_points' => 10,
        'fm_due_date' => Carbon::now()->addDays(7),
        'hm_due_date' => Carbon::now()->addDays(14),
    ])->assertRedirect(route('modules.show', $module->id));

    assertDatabaseHas('lab_tasks', [
        'id' => $labTask->id,
        'task_name' => 'Task 1.5',
    ]);
});

test('student can not edit task', function () {
    $student = User::factory()->create();
    $student->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $student->id]);
    $module->users()->attach($student->id);

    $labTask = LabTask::factory()->create(['module_id' => $module->id]);

    actingAs($student)->patch(route('lab-task.update', [$module, $labTask]), [
        'task_name' => 'Task 1.5',
        'description' => 'Test Task',
        'total_points' => 10,
        'fm_due_date' => Carbon::now()->addDays(7),
        'hm_due_date' => Carbon::now()->addDays(14),
    ])->assertForbidden();

    assertDatabaseMissing('lab_tasks', [
        'id' => $labTask->id,
        'task_name' => 'Task 1.5',
    ]);
});

test('lab tasks cant be made with large numbers', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach($lecturer->id);

    actingAs($lecturer)->post(route('lab-task.store', $module), [
        'task_name' => 'Task 1',
        'description' => 'Test Task',
        'total_points' => 15,
        'fm_due_date' => Carbon::now()->addDays(7),
        'hm_due_date' => Carbon::now()->addDays(14),
    ])->assertSessionHasErrors('total_points');

    assertDatabaseMissing('lab_tasks', [
        'task_name' => 'Task 1',
    ]);
});

test('lab tasks cant be made with negative numbers', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach($lecturer->id);

    actingAs($lecturer)->post(route('lab-task.store', $module), [
        'task_name' => 'Task 1',
        'description' => 'Test Task',
        'total_points' => -5,
        'fm_due_date' => Carbon::now()->addDays(7),
        'hm_due_date' => Carbon::now()->addDays(14),
    ])->assertSessionHasErrors('total_points');

    assertDatabaseMissing('lab_tasks', [
        'task_name' => 'Task 1',
    ]);
});



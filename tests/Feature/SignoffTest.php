<?php
use function Pest\Laravel\{assertDatabaseHas, get};
use function Pest\Laravel\{actingAs};
use App\Models\User;
use App\Models\Module;
use App\Models\LabTask;
use App\Models\LabTaskUser;
use Livewire\Livewire;
use App\Livewire\SignOffManager;
use Illuminate\Support\Facades\DB;

beforeEach(function() {
    DB::table('roles')->insert([
        ['id'=>1, 'name'=>'Student'],
        ['id'=>2, 'name'=>'Teaching Assistant'],
        ['id'=>3, 'name'=>'Lecturer'],
        ['id'=>4, 'name'=>'Admin'],
    ]);
});

test('full marks awarded correctly', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $student = User::factory()->create();
    $student->roles()->attach(1);

    $module = Module::factory()->create([
        'module_leader_id' => $lecturer->id,
    ]);
    $module->users()->attach([$lecturer->id, $student->id]);

    $labTask = LabTask::factory()->create([
        'module_id' => $module->id,
        'total_points' => 10,
    ]);

    actingAs($lecturer);

    Livewire::test(SignOffManager::class, [
        'module' => $module,
        'student' => $student,
    ])->call('signOff', $labTask->id, 'full');

    assertDatabaseHas('lab_task_users', [
        'lab_task_id' => $labTask->id,
        'user_id' => $student->id,
        'signed_off_by' => $lecturer->id,
        'marks_awarded' => 10,
    ]);
});

test('half marks awarded correctly', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $student = User::factory()->create();
    $student->roles()->attach(1);

    $module = Module::factory()->create([
        'module_leader_id' => $lecturer->id,
    ]);
    $module->users()->attach([$lecturer->id, $student->id]);

    $labTask = LabTask::factory()->create([
        'module_id' => $module->id,
        'total_points' => 10,
    ]);

    actingAs($lecturer);

    Livewire::test(SignOffManager::class, [
        'module' => $module,
        'student' => $student,
    ])->call('signOff', $labTask->id, 'half');

    assertDatabaseHas('lab_task_users', [
        'lab_task_id' => $labTask->id,
        'user_id' => $student->id,
        'signed_off_by' => $lecturer->id,
        'marks_awarded' => 5,
    ]);
});

test('student cannot sign off', function () {
    $student = User::factory()->create();
    $student->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($student->id);

    $labTask = LabTask::factory()->create([
        'module_id' => $module->id,
        'total_points' => 10,
    ]);

    actingAs($student);

    Livewire::test(SignOffManager::class, [
        'module' => $module,
        'student' => $student,
    ])->call('signOff', $labTask->id, 'full')->assertForbidden();
});

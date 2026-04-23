<?php

use App\Models\Module;
use App\Models\User;
use App\Livewire\ModuleEnrolmentManager;

use Illuminate\Support\Facades\Notification;
use App\Notifications\RemovedFromModuleNotification;
use App\Notifications\ModuleEnrollmentNotification;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs};
use function Pest\Laravel\{assertDatabaseHas};
use function Pest\Laravel\{assertDatabaseMissing};

use Illuminate\Support\Facades\DB;

beforeEach(function() {
    DB::table('roles')->insert([
        ['id'=>1, 'name'=>'Student'],
        ['id'=>2, 'name'=>'Teaching Assistant'],
        ['id'=>3, 'name'=>'Lecturer'],
        ['id'=>4, 'name'=>'Admin'],
    ]);
});

test('lecturer can enrol a user', function() {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach($lecturer->id);

    actingAs($lecturer);
    Livewire::test(ModuleEnrolmentManager::class,['module' => $module])
    ->call('enrol', $user->id);

    assertDatabaseHas('module_user', [
        'module_id' => $module->id,
        'user_id' => $user->id,
    ]);
});

test('lecturer can unenrol a user', function() {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach([$lecturer->id, $user->id]);

    actingAs($lecturer);
    Livewire::test(ModuleEnrolmentManager::class,['module' => $module])
    ->call('unenrol', $user->id);

    assertDatabaseMissing('module_user', [
        'module_id' => $module->id,
        'user_id' => $user->id,
    ]);
});

test('student cant enrol a user', function() {
    $student = User::factory()->create();
    $student->roles()->attach(1);

    $student2 = User::factory()->create();
    $student2->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach($student->id);

    actingAs($student);
    Livewire::test(ModuleEnrolmentManager::class,['module' => $module])
    ->call('enrol', $student2->id)
    ->assertForbidden();
});

test('student cant unenrol a user', function() {
    $student = User::factory()->create();
    $student->roles()->attach(1);

    $student2 = User::factory()->create();
    $student2->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach([$student->id, $student2->id]);

    actingAs($student);
    Livewire::test(ModuleEnrolmentManager::class,['module' => $module])
    ->call('unenrol', $student2->id)
    ->assertForbidden();
});

test('enrolment email is sent', function() {
    Notification::fake();
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach($lecturer->id);

    actingAs($lecturer);
    Livewire::test(ModuleEnrolmentManager::class,['module' => $module])
    ->call('enrol', $user->id);

    Notification::assertSentTo($user, ModuleEnrollmentNotification::class);
});

test('unenrolment email is sent', function() {
    Notification::fake();
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach([$lecturer->id, $user->id]);

    actingAs($lecturer);
    Livewire::test(ModuleEnrolmentManager::class,['module' => $module])
    ->call('unenrol', $user->id);

    Notification::assertSentTo($user, RemovedFromModuleNotification::class);
});

test('enrolment filtered by name', function() {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $user = User::factory()->create(['name'=>'John Doe']);
    $user->roles()->attach(1);

    $user = User::factory()->create(['name'=>'Jane Doe']);
    $user->roles()->attach(1);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach($lecturer->id);

    actingAs($lecturer);
    Livewire::test(ModuleEnrolmentManager::class, ['module' => $module])
    ->set('nameSearch', 'John')
    ->assertSee('John Doe')
    ->assertDontSee('Jane Doe');
});

test('enrolment filtered by role', function() {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $user = User::factory()->create(['name'=>'John Doe']);
    $user->roles()->attach(1);

    $user = User::factory()->create(['name'=>'Jane Doe']);
    $user->roles()->attach(2);

    $module = Module::factory()->create(['module_leader_id' => $lecturer->id]);
    $module->users()->attach($lecturer->id);

    actingAs($lecturer);
    Livewire::test(ModuleEnrolmentManager::class, ['module' => $module])
    ->set('roleFilter', 'Student')
    ->assertSee('John Doe')
    ->assertDontSee('Jane Doe');
});
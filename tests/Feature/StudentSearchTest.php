<?php

use App\Models\Module;
use App\Models\User;
use App\Livewire\StudentSearchManager;
use Livewire\Livewire;
use function Pest\Laravel\{actingAs};

beforeEach(function() {
    DB::table('roles')->insert([
        ['id'=>1, 'name'=>'Student'],
        ['id'=>2, 'name'=>'Teaching Assistant'],
        ['id'=>3, 'name'=>'Lecturer'],
        ['id'=>4, 'name'=>'Admin'],
    ]);
});

test('lecturers are not seen in student search', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $module = Module::factory()->create();
    $module->users()->attach($lecturer->id);

    actingAs($lecturer);

    Livewire::test(StudentSearchManager::class, ['module' => $module])->assertDontSee($lecturer->name);
});

test('all users shown on empty input', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $student1 = User::factory()->create();
    $student1->roles()->attach(1);

    $student2 = User::factory()->create();
    $student2->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach([$lecturer->id, $student1->id, $student2->id]);

    actingAs($lecturer);

    Livewire::test(StudentSearchManager::class, ['module' => $module])->set('nameSearch','')->assertSee([$student1->name, $student2->name]);
});

test('searched user shown on email', function () {
    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $student1 = User::factory()->create();
    $student1->roles()->attach(1);

    $student2 = User::factory()->create();
    $student2->roles()->attach(1);

    $module = Module::factory()->create();
    $module->users()->attach([$lecturer->id, $student1->id, $student2->id]);

    actingAs($lecturer);

    Livewire::test(StudentSearchManager::class, ['module' => $module])
    ->set('nameSearch',$student1->email)
    ->assertSee($student1->name)
    ->assertDontSee($student2->name);
});

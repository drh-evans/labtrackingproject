<?php

use App\Livewire\UserManagement;
use function Pest\Laravel\{get};
use function Pest\Laravel\{actingAs};
use App\Models\User;
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

test('student cant access admin', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);

    actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
});

test('ta cant access admin', function () {
    $student = User::factory()->create();
    $student->roles()->attach(2);

    actingAs($student)->get(route('admin.dashboard'))->assertForbidden();
});

test('lecturer cant access admin', function () {
    $student = User::factory()->create();
    $student->roles()->attach(3);

    actingAs($student)->get(route('admin.dashboard'))->assertForbidden();
});

test('admin can access admin', function () {
    $user = User::factory()->create();
    $user->roles()->attach(4);

    actingAs($user)->get(route('admin.dashboard'))->assertOk();
});

test('guest redirected to login', function () {
    get(route('dashboard'))->assertRedirect(route('login'));
});

test('student cant delete another user', function () {
    $user = User::factory()->create();
    $user->roles()->attach(1);
    $target = User::factory()->create();
    $target->roles()->attach(1);

    actingAs($user);
    Livewire::test(UserManagement::class)->call('deleteUser', $target->id)->assertForbidden();
});

test('admin can delete another user', function () {
    $user = User::factory()->create();
    $user->roles()->attach(4);
    $target = User::factory()->create();
    $target->roles()->attach(1);

    actingAs($user);
    Livewire::test(UserManagement::class)->call('deleteUser', $target->id)->assertOk();
});

test('admin cant delete themselves', function () {
    $user = User::factory()->create();
    $user->roles()->attach(4);

    actingAs($user);
    Livewire::test(UserManagement::class)->call('deleteUser', $user->id)->assertForbidden();
});
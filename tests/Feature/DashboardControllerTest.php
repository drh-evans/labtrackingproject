<?php

use App\Models\User;
use App\Models\Module;
use App\Models\LabTask;
use App\Models\LabTaskUser;
use App\Models\Queue;
use App\Models\QueueEntry;
use Carbon\Carbon;

use function Pest\Laravel\{actingAs};

use Illuminate\Support\Facades\DB;

beforeEach(function() {
    DB::table('roles')->insert([
        ['id'=>1, 'name'=>'Student'],
        ['id'=>2, 'name'=>'Teaching Assistant'],
        ['id'=>3, 'name'=>'Lecturer'],
        ['id'=>4, 'name'=>'Admin'],
    ]);
});

test('admin is redirected to admin dashboard', function() {
    $user = User::factory()->create();
    $user->roles()->attach(4);

    actingAs($user)
    ->get(route('dashboard'))
    ->assertRedirect(route('admin.dashboard'));
});

test('Lecturer has access to staff dashboard', function() {
    $user = User::factory()->create();
    $user->roles()->attach(3);

    actingAs($user)
    ->get(route('dashboard'))
    ->assertOk()
    ->assertViewIs('dashboards.staff');
});

test('TA has access to staff dashboard', function() {
    $user = User::factory()->create();
    $user->roles()->attach(2);

    actingAs($user)
    ->get(route('dashboard'))
    ->assertOk()
    ->assertViewIs('dashboards.staff');
});

test('student has access to student dashboard', function() {
    $user = User::factory()->create();
    $user->roles()->attach(1);
    actingAs($user)
    ->get(route('dashboard'))
    ->assertOk()
    ->assertViewIs('dashboards.student');
});


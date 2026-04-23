<?php
use App\Models\Module;
use App\Models\User;
use App\Models\LabTask;

use Illuminate\Support\Facades\Notification;
use App\Notifications\WeeklyReportNotification;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

beforeEach(function() {
    DB::table('roles')->insert([
        ['id'=>1, 'name'=>'Student'],
        ['id'=>2, 'name'=>'Teaching Assistant'],
        ['id'=>3, 'name'=>'Lecturer'],
        ['id'=>4, 'name'=>'Admin'],
    ]);
});

test('command notifies when 2 or more tasks missed', function () {
    Notification::fake();

    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create([
        'module_leader_id' => $lecturer->id, 
        'year_taught' => now()->year,
        ]);
    $module->users()->attach([$lecturer->id, $user->id]);

    LabTask::factory()->create([
        'module_id' => $module->id,
        'fm_due_date' => Carbon::now()->subDays(7),
        'hm_due_date' => Carbon::now()->subDays(4),
    ]);

    LabTask::factory()->create([
        'module_id' => $module->id,
        'fm_due_date' => Carbon::now()->subDays(14),
        'hm_due_date' => Carbon::now()->subDays(10),
    ]);

    $this->artisan('app:missed-consecutive-labs-command');
    Notification::assertSentTo($lecturer, WeeklyReportNotification::class);
});

test('no email sent if no lecturer record exists', function () {
    Notification::fake();

    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create([
        'module_leader_id' => null, 
        'year_taught' => now()->year,
        ]);
    $module->users()->attach($user->id);

    LabTask::factory()->create([
        'module_id' => $module->id,
        'fm_due_date' => Carbon::now()->subDays(7),
        'hm_due_date' => Carbon::now()->subDays(4),
    ]);

    LabTask::factory()->create([
        'module_id' => $module->id,
        'fm_due_date' => Carbon::now()->subDays(14),
        'hm_due_date' => Carbon::now()->subDays(10),
    ]);

    $this->artisan('app:missed-consecutive-labs-command');
    Notification::assertNothingSent();
});

test('old modules are skipped', function () {
    Notification::fake();

    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create([
        'module_leader_id' => $lecturer->is, 
        'year_taught' => 2023,
        ]);
    $module->users()->attach([$lecturer->id, $user->id]);

    LabTask::factory()->create([
        'module_id' => $module->id,
        'fm_due_date' => Carbon::now()->subDays(7),
        'hm_due_date' => Carbon::now()->subDays(4),
    ]);

    LabTask::factory()->create([
        'module_id' => $module->id,
        'fm_due_date' => Carbon::now()->subDays(14),
        'hm_due_date' => Carbon::now()->subDays(10),
    ]);

    $this->artisan('app:missed-consecutive-labs-command');
    Notification::assertNothingSent();
});

test('non consecutive labs do not send notification', function () {
    Notification::fake();

    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create([
        'module_leader_id' => $lecturer->is, 
        'year_taught' => now()->year,
        ]);
    $module->users()->attach([$lecturer->id, $user->id]);

    LabTask::factory()->create([
        'module_id' => $module->id,
        'fm_due_date' => Carbon::now()->subDays(20),
        'hm_due_date' => Carbon::now()->subDays(15),
    ]);

    $task = LabTask::factory()->create([
        'module_id' => $module->id,
        'fm_due_date' => Carbon::now()->subDays(15),
        'hm_due_date' => Carbon::now()->subDays(10),
    ]);

    $task->users()->attach($user->id, [
        'signed_off_by' => $lecturer->id,
        'marks_awarded' => 10,
        'completed_at' => now(),
    ]);

    LabTask::factory()->create([
        'module_id' => $module->id,
        'fm_due_date' => Carbon::now()->subDays(7),
        'hm_due_date' => Carbon::now()->subDays(4),
    ]);

    $this->artisan('app:missed-consecutive-labs-command');
    Notification::assertNothingSent();
});

test('only one missed lab does not send notification', function () {
    Notification::fake();

    $lecturer = User::factory()->create();
    $lecturer->roles()->attach(3);

    $user = User::factory()->create();
    $user->roles()->attach(1);

    $module = Module::factory()->create([
        'module_leader_id' => $lecturer->is, 
        'year_taught' => now()->year,
        ]);
    $module->users()->attach([$lecturer->id, $user->id]);

    LabTask::factory()->create([
        'module_id' => $module->id,
        'fm_due_date' => Carbon::now()->subDays(20),
        'hm_due_date' => Carbon::now()->subDays(15),
    ]);

    $this->artisan('app:missed-consecutive-labs-command');
    Notification::assertNothingSent();
});
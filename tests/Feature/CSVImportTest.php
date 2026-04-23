<?php
use App\Models\User;
use App\Models\Module;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Notification;
use App\Notifications\ModuleEnrollmentNotification;

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

test('admin can import users', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(4);

    $csv = "email,name,role\njohn.j@swansea.test,Dr John Jones,Lecturer\n";
    $file = UploadedFile::fake()->createWithContent('users.csv',$csv);
    actingAs($admin)->post(route('users.import'), ['csv'=> $file])->assertRedirect();

    assertDatabaseHas('users', ['email' => 'john.j@swansea.test']);
});

test('admin can import modules', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(4);

    $lecturer = User::factory()->create([
        'email' => 'j.jones@swansea.test',
    ]);
    $lecturer->roles()->attach(3);

    $csv = "module_code,module_name,coordinator_email,year_group,year_taught\n
            CSCM400,TestModule,j.jones@swansea.test,3,2026\n";

    $file = UploadedFile::fake()->createWithContent('modules.csv', $csv);

    actingAs($admin)->post(route('modules.import'), ['csv'=> $file])->assertRedirect();

    assertDatabaseHas('modules', [
        'module_code' => 'CSCM400',
        'year_taught' => 2026,
        ]);
});

test('admin missing required fields in module', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(4);

    $lecturer = User::factory()->create([
        'email' => 'j.jones@swansea.test',
    ]);
    $lecturer->roles()->attach(3);

    $csv = "module_code,module_name,coordinator_email,year_group,year_taught\n
            CSCM400,,j.jones@swansea.test,3,2026\n";

    $file = UploadedFile::fake()->createWithContent('modulesByYear.csv', $csv);

    actingAs($admin)->post(route('modules.import'), ['csv'=> $file])->assertSessionHas('error');

    assertDatabaseMissing('modules', [
        'module_code' => 'CSCM400',
        'year_taught' => 2026,
        ]);
});

test('desired coordinator doesnt exist resulting in failed import', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(4);

    $csv = "module_code,module_name,coordinator_email,year_group,year_taught\n
            CSCM400,TestModule,anon@swansea.test,3,2026\n";

    $file = UploadedFile::fake()->createWithContent('modulesByYear.csv', $csv);

    actingAs($admin)->post(route('modules.import'), ['csv'=> $file])->assertSessionHas('error');

    assertDatabaseMissing('modules', [
        'module_code' => 'CSCM400',
        'year_taught' => 2026,
        ]);
});

test('desired coordinator is not staff resulting in failed import', function () {
    $admin = User::factory()->create([
        'email' => 'staff@swansea.test',
    ]);
    $admin->roles()->attach(4);

    $csv = "module_code,module_name,coordinator_email,year_group,year_taught\n
            CSCM400,TestModule,staff@swansea.test,3,2026\n";

    $file = UploadedFile::fake()->createWithContent('modulesByYear.csv', $csv);

    actingAs($admin)->post(route('modules.import'), ['csv'=> $file])->assertSessionHas('error');

    assertDatabaseMissing('modules', [
        'module_code' => 'CSCM400',
        'year_taught' => 2026,
        ]);
});

test('import fails if module already exists', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(4);

    $lecturer = User::factory()->create([
        'email' => 'j.jones@swansea.test',
    ]);
    $lecturer->roles()->attach(3);

    Module::factory()->create([
        'module_code' => 'CSCM400',
        'module_name' => 'TestModule',
        'year_taught' => 2026,
    ]);

    $csv = "module_code,module_name,coordinator_email,year_group,year_taught\n
            CSCM400,TestModule,j.jones@swansea.test,3,2026\n";

    $file = UploadedFile::fake()->createWithContent('modulesByYear.csv', $csv);

    actingAs($admin)->post(route('modules.import'), ['csv'=> $file])->assertRedirect();

    assertDatabaseCount('modules', 1);
});

test('wrong role causes error', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(4);

    $csv = "email,name,role\njohn.j@swansea.test,Dr John Jones,SeniorLecturer\n";
    $file = UploadedFile::fake()->createWithContent('users.csv',$csv);
    actingAs($admin)->post(route('users.import'), ['csv'=> $file])->assertSessionHas('error');

    assertDatabaseMissing('users', ['email' => 'john.j@swansea.test']);
});

test('missing header fails', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(4);

    $csv = "email,name\njohn.j@swansea.test,Dr John Jones\n";
    $file = UploadedFile::fake()->createWithContent('users.csv',$csv);
    actingAs($admin)->post(route('users.import'), ['csv'=> $file])->assertSessionHas('error');
});

// enrolment imports

test('admin can import enrolment', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(4);

    $student = User::factory()->create([
        'email' => '21221267@swansea.test',
    ]);

    $student->roles()->attach(1);

    $module = Module::factory()->create([
        'module_code' => 'CS101',
        'year_taught' => 2026,
    ]);

    $csv = "email,module_code,year_taught\n
            21221267@swansea.test,CS101,2026\n";

    $file = UploadedFile::fake()->createWithContent('moduleEnrolment.csv', $csv);

    actingAs($admin)->post(route('enrolment.import'), ['csv'=> $file])->assertRedirect();

    assertDatabaseHas('module_user', [
        'module_id' => $module->id,
        'user_id' => $student->id,
        ]);
});


test('enrolment notification sent on import', function () {
    Notification::fake();
    $admin = User::factory()->create();
    $admin->roles()->attach(4);

    $student = User::factory()->create([
        'email' => '21221267@swansea.test',
    ]);

    $student->roles()->attach(1);

    $module = Module::factory()->create([
        'module_code' => 'CS101',
        'year_taught' => 2026,
    ]);

    $csv = "email,module_code,year_taught\n
            21221267@swansea.test,CS101,2026\n";

    $file = UploadedFile::fake()->createWithContent('moduleEnrolment.csv', $csv);

    actingAs($admin)->post(route('enrolment.import'), ['csv'=> $file])->assertRedirect();

    Notification::assertSentTo($student, ModuleEnrollmentNotification::class);
});

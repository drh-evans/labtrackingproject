<?php
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\{actingAs};
use function Pest\Laravel\{assertDatabaseHas};
use function Pest\Laravel\{assertDatabaseMissing};


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

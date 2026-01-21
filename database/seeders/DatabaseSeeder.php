<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesTableSeeder::class);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'System Admin',
            'email' => 'systemadmin@swansea.test',
            'password' => 'testtest',
        ])->roles()->attach(4);

        $module = Module::create([
            'module_code' => 'CS101',
            'module_name' => 'Introduction to Computer Science',
        ]);

        $student = User::factory()->create([
            'name' => 'David Evans',
            'email' => '2316644@swansea.test',
            'password' => 'testtest',
            ]);

        $student->roles()->attach(1);

        if ($student) {
            $module->users()->attach($student->id);
        }

        $module = Module::create([
            'module_code' => 'CS110',
            'module_name' => 'Introduction to Programming',
        ]);
    }
}

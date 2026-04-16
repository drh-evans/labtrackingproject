<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesTableSeeder::class);

        // User::factory(10)->create();
        $this->call(UsersTableSeeder::class);

        $module = Module::create([
            'module_code' => 'CS110',
            'module_name' => 'Programming 1',
            'module_leader_id'=>2,
            'year_taught'=>2023,
            'year_group'=>1,
        ]);

        $module->users()->attach(2);

        $module = Module::create([
            'module_code' => 'CS115',
            'module_name' => 'Programming 2',
            'module_leader_id'=>3,
            'year_taught'=>2023,
            'year_group'=>1,
        ]);

        $module->users()->attach(3);

        $module = Module::create([
            'module_code' => 'CS130',
            'module_name' => 'Professional Issues 1: Computers & Society',
            'module_leader_id'=>4,
            'year_taught'=>2023,
            'year_group'=>1,
        ]);

        $module->users()->attach(4);

        $module = Module::create([
            'module_code' => 'CS135',
            'module_name' => 'Professional Issues 2: Software Development',
            'module_leader_id'=>1,
            'year_taught'=>2023,
            'year_group'=>1,
        ]);

        $module->users()->attach(1);

        $module = Module::create([
            'module_code' => 'CS150',
            'module_name' => 'Concepts of Computer Science',
            'module_leader_id'=>6,
            'year_taught'=>2023,
            'year_group'=>1,
        ]);

        $module->users()->attach(6);

        $module = Module::create([
            'module_code' => 'CS165',
            'module_name' => 'Introduction to Data Science',
            'module_leader_id'=>7,
            'year_taught'=>2023,
            'year_group'=>1,
        ]);
        $module->users()->attach(7);

        $module = Module::create([
            'module_code' => 'CS170',
            'module_name' => 'Modelling Computing Systems 1',
            'module_leader_id'=>5,
            'year_taught'=>2023,
            'year_group'=>1,
        ]);

        $module->users()->attach(5);

        $module = Module::create([
            'module_code' => 'CS175',
            'module_name' => 'Modelling Computing Systems 2',
            'module_leader_id'=>5,
            'year_taught'=>2023,
            'year_group'=>1,
        ]);

        $module->users()->attach(5);

        $user = User::factory()->create([
            'name' => 'David Evans',
            'email' => '2316644@swansea.test',
            'password' => 'testtest',
            ]);

        $user->roles()->attach(1);
        $user->modules()->attach([1,2,3,4,5,6,7,8]);
    }
}

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

        $module = Module::create([
            'module_code' => 'CS101',
            'module_name' => 'Introduction to Computer Science',
        ]);

        $module = Module::create([
            'module_code' => 'CS110',
            'module_name' => 'Introduction to Programming',
        ]);

        $this->call(UsersTableSeeder::class);
    }
}

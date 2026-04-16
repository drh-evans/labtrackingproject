<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    User::factory()->create([
            'name' => 'Prof. Markus Roggenbach',
            'email' => 'm.roggenbach@swansea.test',
            'password' => 'testtest',
        ])->roles()->attach(3);

        User::factory()->create([
            'name' => 'Dr. Neal Harman',
            'email' => 'n.harman@swansea.test',
            'password' => 'testtest',
        ])->roles()->attach(3);

        User::factory()->create([
            'name' => 'Dr. Troy Astarte',
            'email' => 't.k.astarte@swansea.test',
            'password' => 'testtest',
        ])->roles()->attach(3);

        User::factory()->create([
            'name' => 'Dr. Nicholas Micallef',
            'email' => 'nicolas.micallef@swansea.test',
            'password' => 'testtest',
        ])->roles()->attach(3);

        User::factory()->create([
            'name' => 'Prof. Faron Moller',
            'email' => 'f.moller@swansea.test',
            'password' => 'testtest',
        ])->roles()->attach(3);

        User::factory()->create([
            'name' => 'Dr. Jens Blanck',
            'email' => 'j.blanck@swansea.test',
            'password' => 'testtest',
        ])->roles()->attach(3);

        User::factory()->create([
            'name' => 'Dr. Siemeng Qiu',
            'email' => 's.qiu@swansea.test',
            'password' => 'testtest',
        ])->roles()->attach(3);

        User::factory()->create([
            'name' => 'Mr. Randell Gaya',
            'email' => 'randell.gaya@swansea.test',
            'password' => 'testtest',
        ])->roles()->attach(3);

        User::factory()->create([
            'name' => 'System Admin',
            'email' => 'systemadmin@labtracker.com',
            'password' => 'testtest',
        ])->roles()->attach(4);

            User::factory()->create([
            'name' => 'Alex Vesely',
            'email' => 'a.vesely@swansea.test',
            'password' => 'testtest',
            ])->roles()->attach(2);

            User::factory()->create([
            'name' => 'Ed Tickle',
            'email' => 'e.tickle@swansea.test',
            'password' => 'testtest',
            ])->roles()->attach(2);

        User::factory(30)->create([
            'password' => 'testtest',
        ])->each(function($user) {
            $user->roles()->attach(1);
        });
    }
}

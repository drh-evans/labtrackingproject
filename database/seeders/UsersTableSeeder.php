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
            'name' => 'System Admin',
            'email' => 'systemadmin@swansea.test',
            'password' => 'testtest',
        ])->roles()->attach(4);

        User::factory()->create([
            'name' => 'David Evans',
            'email' => '2316644@swansea.test',
            'password' => 'testtest',
            ])->roles()->attach(1);

        User::factory(30)->create([
            'password' => 'testtest',
        ])->each(function($user) {
            $user->roles()->attach(1);
        });
    }
}

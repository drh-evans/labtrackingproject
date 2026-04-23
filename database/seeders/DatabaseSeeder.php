<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Module;
use App\Models\LabTask;
use App\Models\Queue;
use App\Models\QueueEntry;
use App\Models\Message;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

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

        $user = User::factory()->create([
            'name' => 'David Evans',
            'email' => '2316644@swansea.test',
            'password' => 'testtest',
            ]);

        $user->roles()->attach(1);

        $module = Module::create([
            'module_code' => 'CS110',
            'module_name' => 'Programming 1',
            'module_leader_id'=>2,
            'year_taught'=>2026,
            'year_group'=>1,
        ]);

        $module->users()->attach(2);

        $module = Module::create([
            'module_code' => 'CS115',
            'module_name' => 'Programming 2',
            'module_leader_id'=>3,
            'year_taught'=>2026,
            'year_group'=>1,
        ]);

        $module->queue->update(['is_open' => true]);
        $module->chatroom->update(['is_open' => true]);

        LabTask::factory()->create([
            'module_id' => $module->id,
            'task_name' => 'Task 1',
            'fm_due_date' => Carbon::now()->subDays(20),
            'hm_due_date' => Carbon::now()->subDays(15),
        ]);

        LabTask::factory()->create([
            'module_id' => $module->id,
            'task_name' => 'Task 2',
            'fm_due_date' => Carbon::now()->subDays(10),
            'hm_due_date' => Carbon::now()->subDays(5),
        ]);

        $task = LabTask::factory()->create([
            'module_id' => $module->id,
            'task_name' => 'Task 3',
            'fm_due_date' => Carbon::now()->addDays(5),
            'hm_due_date' => Carbon::now()->addDays(10),
        ]);

        $module->users()->attach([3,$user->id]);
        $task->users()->attach($user->id, ['signed_off_by' => 3, 'marks_awarded' => 10, 'completed_at' => now() ]);

        for($i = 0; $i < 6; $i++) {
            $joinedAt = Carbon::now()->subMinutes(60 - ($i * 10));
            $leftAt = $joinedAt->copy()->addMinutes(rand(2,10));
            QueueEntry::factory()->count(rand(1, 30))->create([
                'queue_id' => $module->queue->id,
                'student_id' => $user->id,
                'queue_position' => null,
                'joined_at' => $joinedAt,
                'left_at' => $leftAt,
            ]);
        }

        $messageContent = [
            'what is null pointer exception?', 
            'What is polymorphism?', 
            'What is OOP inheritance?',
            'What is abstraction OOP?',
            'What is OOP?',
            'OOP is hard',
            'OOP is easy',
            'OOP',
            'OOP is Object Oriented Programming',
            'How do I do OOP in Java',
            'What is a Java Static',
            'Task 2 is hard',
            'What is a Java Enum',
            ];

        for($i = 0; $i < 6; $i++) {
            $createdAt = Carbon::now()->subMinutes(60 - ($i * 10));

            $messageCount = rand(1, 10);
            for($j = 0; $j < $messageCount; $j++) {
                Message::create([
                'chatroom_id' => $module->chatroom->id,
                'user_id' => $user->id,
                'body' => $messageContent[array_rand($messageContent)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            }
        }

        LabTask::factory()->create([
            'module_id' => $module->id,
            'task_name' => 'Task 4',
            'fm_due_date' => Carbon::now()->addDays(10),
            'hm_due_date' => Carbon::now()->addDays(13),
        ]);

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

        $user->modules()->attach([1,3,4,5,6,7,8]);
    }
}

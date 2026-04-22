<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Queue;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QueueEntry>
 */
class QueueEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'queue_id' => Queue::factory(),
            'student_id' => User::factory(),
            'queue_position' => $this->faker->numberBetween(1, 30),
            'task' => 'Task' .  $this->faker->numberBetween(1, 10),
            'location' => 'CoFo' . $this->faker->randomElement([104, 204, 202, 104]),
            'assistance_type' => $this->faker->randomElement(['Assistance', 'Sign-off']),
            'information' => null,
            'joined_at' => now(),
            'left_at' => null,
        ];
    }
}

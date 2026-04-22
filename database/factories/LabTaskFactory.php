<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Module;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LabTask>
 */
class LabTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lecturer = User::factory()->create();
        $lecturer->roles()->attach(3);
        $fullMarksDeadline = Carbon::now()->addDays($this->faker->numberBetween(7, 32));
        $halfMarksDeadline = $fullMarksDeadline->copy()->addWeeks(2);
        return [
            'module_id' => Module::factory(),
            'created_by' => $lecturer->id,
            'task_name' => 'Task' . $this->faker->numberBetween(1, 10),
            'description' => $this->faker->sentence(),
            'total_points' => 10,
            'fm_due_date' => $fullMarksDeadline,
            'hm_due_date' => $halfMarksDeadline,
        ];
    }
}

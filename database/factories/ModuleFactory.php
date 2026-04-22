<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $yearGroup = $this->faker->numberBetween(0,4);
        $leader = User::factory()->create();
        $leader->roles()->attach(3);

        return [
            'module_code' => 'CS'. $yearGroup . $this->faker->unique()->numberBetween(10,99),
            'module_name' => $this->faker->words(4, true),
            'module_leader_id' => $leader->id,
            'year_taught' => $this->faker->numberBetween(2023, 2026),
            'year_group' => $yearGroup,
            'bg_image_url' => null
        ];
    }
}

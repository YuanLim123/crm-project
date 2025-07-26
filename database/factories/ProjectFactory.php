<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\ProjectStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id, // Randomly assign a existing user
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'end_date' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(ProjectStatus::cases())->value, // Randomly assign a status from the ProjectStatus enum
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => \App\Models\Project::factory(),
            'author_id' => \App\Models\User::factory(),
            'assignee_id' => fake()->boolean(70) ? \App\Models\User::factory() : null,
            'title' => fake()->sentence(6),
            'description' => fake()->optional()->paragraph(3),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+3 months'),
        ];
    }
}

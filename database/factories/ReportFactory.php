<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $periodStart = fake()->dateTimeBetween('-3 months', 'now');
        $periodEnd = fake()->dateTimeBetween($periodStart, '+1 month');

        return [
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'payload' => [
                'total_tasks' => fake()->numberBetween(10, 100),
                'completed_tasks' => fake()->numberBetween(5, 50),
                'active_projects' => fake()->numberBetween(1, 10),
            ],
            'path' => 'reports/' . fake()->uuid() . '.pdf',
        ];
    }
}

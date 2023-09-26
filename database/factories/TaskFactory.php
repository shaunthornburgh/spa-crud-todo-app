<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
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
            'name' => fake()->sentence,
            'priority' => Task::PRIORITIES[array_rand(Task::PRIORITIES)],
            'status' => Task::STATUSES[array_rand(Task::STATUSES)],
            'user_id' => User::factory(),
            'due_by' => fake()->dateTimeBetween('+0 days', '+2 weeks')->format('Y-m-d')
        ];
    }
}

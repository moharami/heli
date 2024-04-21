<?php

namespace Database\Factories;

use App\Enum\StatusEnum;
use App\Models\User;
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
    public function definition()
    {
        $userIds = User::pluck('id');

        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement([StatusEnum::PENDING, StatusEnum::IN_PROGRESS, StatusEnum::COMPLETED]),
            'user_id' => $this->faker->randomElement($userIds),
        ];
    }
}

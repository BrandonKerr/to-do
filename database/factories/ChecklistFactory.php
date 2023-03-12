<?php

namespace Database\Factories;

use App\Models\Definitions\TodoDefinition;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Checklist>
 */
class ChecklistFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            "title" => $this->faker->sentence,
            "user_id" => User::factory(),
        ];
    }

    /**
     * Add a Todo relationship to the model
     *
     * @param TodoDefinition $todoDefinition
     *
     * @return self
     */
    public function withTodo(?TodoDefinition $todoDefinition): self {
        return $this->has(Todo::factory([
            "title" => $todoDefinition?->title ?? $this->faker->sentence,
            "is_complete" => $todoDefinition?->is_complete ?? false,
        ]),
            "todos"
        );
    }
}

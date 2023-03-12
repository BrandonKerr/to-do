<?php

namespace Database\Factories;

use App\Models\Checklist;
use App\Models\Definitions\TodoDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            "name" => $this->faker->name(),
            "email" => $this->faker->unique()->safeEmail(),
            "email_verified_at" => now(),
            "password" => "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", // password
            "remember_token" => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified() {
        return $this->state(function (array $attributes) {
            return [
                "email_verified_at" => null,
            ];
        });
    }

    /**
     * Indicate that the model's is an admin.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin(): self {
        return $this->state(function (array $attributes) {
            return [
                "is_admin" => true,
            ];
        });
    }

    /**
     * Add a checklist relationship to the model
     *
     * @param string|null $title
     * @param array<TodoDefinition>|null $todos
     *
     * @return self
     */
    public function withChecklist(?string $title = null, ?array $todos = null): self {
        return $this->has(Checklist::factory([
            "title" => $title ?? $this->faker->words(rand(1, 5), true),
        ])
            ->when(! is_null($todos), function (ChecklistFactory $factory) use ($todos) {
                foreach ($todos as $todo) {
                    $factory = $factory->withTodo($todo);
                }

                return $factory;
            }),
            "checklists"
        );
    }
}

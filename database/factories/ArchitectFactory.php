<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Architect;
use App\Models\ArchitectType;
use App\Models\Specifier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Architect>
 */
class ArchitectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        return [
            'architect_name' => fake()->company(),
            'architect_rep_id' => User::inRandomOrder()->first()->id,
            'architect_type_id' => ArchitectType::inRandomOrder()->first()->id,
            'class_id' => fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\UserRole;
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
     * Configure the model factory.
     */
    #[\Override]
    public function configure(): static
    {
        return $this->afterCreating(function (Architect $architect) {
            $architect->addresses()->createMany(
                Address::factory()->count(3)->make(
                    [
                        'name' => $architect->architect_name,
                        'email_address' => fake()->companyEmail(),
                    ]
                )->toArray()
            );

            Specifier::factory()->count(2)
                ->for($architect)
                ->hasAddress(function (array $attributes, Specifier $specifier) {
                    return [
                        'name' => $specifier->first_name . " " . $specifier->last_name,
                        'email_address' => fake()->email(),
                    ];
                })
                ->create();
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        return [
            'architect_name' => fake()->unique()->company(),
            'architect_rep_id' =>
            User::where('user_role_id', '>=', UserRole::ARCHREP)->inRandomOrder()->first()->id ??
                User::factory()->state(['user_role_id' => UserRole::ARCHREP]),
            'architect_type_id' => ArchitectType::inRandomOrder()->first()->id,
            'class_id' => fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
        ];
    }
}

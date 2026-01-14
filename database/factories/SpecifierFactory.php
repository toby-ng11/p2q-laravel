<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Specifier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specifier>
 */
class SpecifierFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    #[\Override]
    public function configure(): static
    {
        return $this->afterCreating(function (Specifier $specifier) {
            $specifier->address()->create(
                Address::factory()->count(1)->make(
                    [
                        'name' => $specifier->first_name . " " . $specifier->last_name,
                        'email_address' => fake()->email(),
                    ]
                )->toArray()
            );
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
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'job_title' => fake()->jobTitle(),
        ];
    }
}

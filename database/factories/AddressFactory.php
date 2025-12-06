<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\en_US\Address;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
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
            'phys_address1' => fake()->streetAddress(),
            'phys_address2' => fake()->buildingNumber(),
            'phys_city' => fake()->city(),
            'phys_state' => fake()->stateAbbr(),
            'phys_postal_code' => fake()->postcode(),
            'phys_country' => fake()->country(),
            'central_phone_number' => fake()->phoneNumber(),
            'url' => fake()->url(),
            // we will set name, and addressable in architect and specifier factories
        ];
    }
}

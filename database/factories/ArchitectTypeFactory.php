<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArchitectType>
 */
class ArchitectTypeFactory extends Factory
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
            'architect_type_desc' => fake()->unique()->randomElement([
                'Residential',
                'Commercial',
                'Industrial',
                'Landscape',
                'Interior Design',
                'Urban Planning',
            ]),
        ];
    }
}

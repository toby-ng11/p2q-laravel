<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Architect;
use App\Models\ArchitectType;
use App\Models\Specifier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArchitectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Architect::factory()
            ->count(50)
            ->has(Address::factory()->count(2)
                ->state(function (array $attributes, Architect $architect) {
                    return [
                        'name' => $architect->architect_name,
                        'email_address' => fake()->companyEmail(),
                    ];
                }))
            ->has(Specifier::factory()
                ->count(3)
                ->has(
                    Address::factory()->count(2)
                        ->state(function (array $attributes, Specifier $specifier) {
                            return [
                                'name' => $specifier->first_name . ' ' . $specifier->last_name,
                                'email_address' => fake()->email(),
                            ];
                        })
                ))
            ->create();
    }
}

<?php

namespace Tests\Feature\Architect;

use App\Enums\UserRole;
use App\Models\Architect;
use App\Models\ArchitectType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchitectTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithRole(UserRole $role): User
    {
        return User::factory()->create([
            'user_role_id' => $role,
        ]);
    }

    protected function architectData(array $overrides = []): array
    {
        return array_merge([
            'architect_name'    => fake()->company(),
            'architect_rep_id'  => null,
            'architect_type_id' => ArchitectType::inRandomOrder()->first()->id,
            'class_id'          => fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
        ], $overrides);
    }

    public function test_architect_can_be_created_successfully(): void
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);

        $architect = $this->architectData(['architect_rep_id' => $user->id]);

        $this->actingAs($user)
            ->post(route('architects.store'), $architect)
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('architects', $architect);
    }

    public function test_architect_can_be_updated_successfully_by_archrep(): void
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);

        /** @var Architect $architect */
        $architect = Architect::factory()->create([
            'architect_rep_id' => $user->id,
        ]);

        $editData = $this->architectData([
            'architect_name' => 'New Name',
            'architect_rep_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->put(route('architects.update', [$architect]), $editData)
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('architects', ['architect_name' => 'New Name']);
    }

    public function test_architect_can_be_updated_successfully_by_manager_or_above(): void
    {
        $manager = $this->makeUserWithRole(UserRole::ARCHREP);
        $otherArchrep = User::factory()->create(['user_role_id' => UserRole::ARCHREP]);

        /** @var Architect $architect */
        $architect = Architect::factory()->create([
            'architect_rep_id' => $manager->id,
        ]);

        $editData = $this->architectData([
            'architect_name' => 'New Name',
            'architect_rep_id' => $otherArchrep->id,
        ]);

        $this->actingAs($manager)
            ->put(route('architects.update', [$architect]), $editData)
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('architects', ['architect_name' => 'New Name']);
    }

    public function test_architect_can_be_deleted()
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);
        $architect = Architect::factory()->create([
            'architect_rep_id' => $rep->id,
        ]);

        $this->actingAs($rep)
            ->delete(route('architects.destroy', $architect))
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('architects', [$architect]);
    }
}

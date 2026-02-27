<?php

namespace Tests\Feature\Requests;

use App\Enums\UserRole;
use App\Models\Architect;
use App\Models\ArchitectType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreArchitectRequestTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithRole(UserRole $role): User
    {
        return User::factory()->create([
            'user_role_id' => $role,
        ]);
    }

    protected function architectAttributes(array $overrides = []): array
    {
        return array_merge([
            'architect_name'    => fake()->company(),
            'architect_rep_id'  => null,
            'architect_type_id' => ArchitectType::inRandomOrder()->first()->id,
            'class_id'          => fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
        ], $overrides);
    }

    public function test_architect_cannot_be_created_if_user_role_is_too_low()
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);
        $lowRoleUser = $this->makeUserWithRole(UserRole::GUEST);

        $architect = $this->architectAttributes(['architect_rep_id' => $rep->id]);

        $this->actingAs($lowRoleUser)
            ->post(route('architects.store'), $architect)
            ->assertForbidden();

        $this->assertDatabaseMissing('architects', $architect);
    }

    public function test_arch_rep_can_create_architect_for_themselves()
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);

        $architect = $this->architectAttributes(['architect_rep_id' => $rep->id]);

        $this->actingAs($rep)
            ->post(route('architects.store'), $architect)
            ->assertRedirect();

        $this->assertDatabaseHas('architects', $architect);
    }

    public function test_arch_rep_cannot_create_architect_for_another_rep()
    {
        $rep1 = $this->makeUserWithRole(UserRole::ARCHREP);
        $rep2 = $this->makeUserWithRole(UserRole::ARCHREP);

        $architect = $this->architectAttributes(['architect_rep_id' => $rep2->id]);

        $this->actingAs($rep1)
            ->post(route('architects.store'), $architect)
            ->assertForbidden();

        $this->assertDatabaseMissing('architects', $architect);
    }

    public function test_arch_rep_cannot_assign_manager_as_rep()
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);
        $manager = $this->makeUserWithRole(UserRole::MANAGER);

        $architect = $this->architectAttributes(['architect_rep_id' => $manager->id]);

        $this->actingAs($rep)
            ->post(route('architects.store'), $architect)
            ->assertForbidden();

        $this->assertDatabaseMissing('architects', $architect);
    }

    public function test_manager_can_create_architect_for_any_rep()
    {
        $manager = $this->makeUserWithRole(UserRole::MANAGER);
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);

        $architect = $this->architectAttributes(['architect_rep_id' => $rep->id]);

        $this->actingAs($manager)
            ->post(route('architects.store'), $architect)
            ->assertRedirect();

        $this->assertDatabaseHas('architects', $architect);
    }

    public function test_manager_can_assign_another_manager_as_rep()
    {
        $manager1 = $this->makeUserWithRole(UserRole::MANAGER);
        $manager2 = $this->makeUserWithRole(UserRole::MANAGER);

        $architect = $this->architectAttributes(['architect_rep_id' => $manager2->id]);

        $this->actingAs($manager1)
            ->post(route('architects.store'), $architect)
            ->assertRedirect();

        $this->assertDatabaseHas('architects', $architect);
    }

    public function test_manager_can_assign_another_admin_as_rep()
    {
        $manager1 = $this->makeUserWithRole(UserRole::MANAGER);
        $admin = $this->makeUserWithRole(UserRole::ADMIN);

        $architect = $this->architectAttributes(['architect_rep_id' => $admin->id]);

        $this->actingAs($manager1)
            ->post(route('architects.store'), $architect)
            ->assertRedirect();

        $this->assertDatabaseHas('architects', $architect);
    }
}

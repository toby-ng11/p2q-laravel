<?php

namespace Tests\Feature\Architect;

use App\Enums\UserRole;
use App\Models\Architect;
use App\Models\ArchitectType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchitectRepTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithRole(UserRole $role): User
    {
        return User::factory()->create([
            'user_role_id' => $role,
        ]);
    }

    public function test_admin_can_view_all_architect_reps()
    {
        $admin = $this->makeUserWithRole(UserRole::ADMIN);

        $rep1 = $this->makeUserWithRole(UserRole::ARCHREP);
        $rep2 = $this->makeUserWithRole(UserRole::ARCHREP);

        $this->actingAs($admin)
            ->getJson(route('architect-reps.index'))
            ->assertOk()
            ->assertJsonCount(3) // including admin
            ->assertJsonFragment(['id' => $rep1->id])
            ->assertJsonFragment(['id' => $rep2->id])
            ->assertJsonFragment(['id' => $admin->id]);
    }

    public function test_manager_can_view_all_architect_reps()
    {
        $manager = $this->makeUserWithRole(UserRole::MANAGER);
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);

        $this->actingAs($manager)
            ->getJson(route('architect-reps.index'))
            ->assertOk()
            ->assertJsonFragment(['id' => $rep->id]);
    }

    public function test_architect_rep_can_only_see_themselves(): void
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);
        $otherRep = $this->makeUserWithRole(UserRole::ARCHREP);

        $this->actingAs($rep)
            ->getJson(route('architect-reps.index'))
            ->assertOk()
            ->assertJsonFragment(['id' => $rep->id])
            ->assertJsonMissing(['id' => $otherRep->id]);
    }

    public function test_archrep_can_update_own_architect_except_architect_rep(): void
    {
        $rep1 = $this->makeUserWithRole(UserRole::ARCHREP);
        $rep2 = $this->makeUserWithRole(UserRole::ARCHREP);
        $architect = Architect::factory()->create([
            'architect_rep_id' => $rep1->id,
            'architect_name' => 'Original Name',
        ]);

        $this->actingAs($rep1)
            ->put(
                route('architects.update', $architect),
                [
                    'architect_name' => 'Updated Name',
                    'architect_rep_id' => $rep2->id, // illegal change
                    'architect_type_id' => ArchitectType::inRandomOrder()->first()->id,
                ]
            )
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('architects', [
            'id' => $architect->id,
            'architect_name' => 'Updated Name',
            'architect_rep_id' => $rep1->id, // unchanged
        ]);

        $this->assertDatabaseMissing('architects', [
            'id' => $architect->id,
            'architect_rep_id' => $rep2->id, // should NOT be updated
        ]);

        $this->actingAs($rep1)
            ->get(route('architects.edit', $architect))
            ->assertStatus(200);
    }

    public function test_sales_cannot_access_architect_reps(): void
    {
        $sales = $this->makeUserWithRole(UserRole::SALES);

        $this->actingAs($sales)
            ->getJson(route('architect-reps.index'))
            ->assertForbidden();
    }

    public function test_guest_cannot_access_architect_reps(): void
    {
        $guest = $this->makeUserWithRole(UserRole::GUEST);

        $this->actingAs($guest)
            ->getJson(route('architect-reps.index'))
            ->assertForbidden();
    }
}

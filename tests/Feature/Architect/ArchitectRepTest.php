<?php

namespace Tests\Feature\Architect;

use App\Enums\UserRole;
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

<?php

namespace Tests\Feature\Architect\Policy;

use App\Enums\UserRole;
use App\Models\Architect;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchitectPolicyTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithRole(UserRole $role): User
    {
        return User::factory()->create([
            'user_role_id' => $role,
        ]);
    }

    public function test_any_architect_can_be_deleted_if_rep_role_is_manager_or_above()
    {
        $highRoleUser = $this->makeUserWithRole(UserRole::MANAGER);
        $architect1 = Architect::factory()->create();
        $architect2 = Architect::factory()->create();

        $this->actingAs($highRoleUser)
            ->delete(route('architects.destroy', $architect1))
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->actingAs($highRoleUser)
            ->delete(route('architects.destroy', $architect2))
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('architects', [
            'id' => $architect1->id,
        ]);

        $this->assertDatabaseMissing('architects', [
            'id' => $architect2->id,
        ]);
    }

    public function test_own_architect_can_be_deleted_if_rep_role_is_arch_rep()
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);
        $architect = Architect::factory()->create([
            'architect_rep_id' => $rep->id,
        ]);

        $this->actingAs($rep)
            ->delete(route('architects.destroy', $architect))
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('architects', [
            'id' => $architect->id,
        ]);
    }

    public function test_rep_cannot_delete_other_rep_architects()
    {
        $rep1 = $this->makeUserWithRole(UserRole::ARCHREP);
        $rep2 = $this->makeUserWithRole(UserRole::ARCHREP);
        $otherArchitect = Architect::factory()->create([
            'architect_rep_id' => $rep2->id,
        ]);

        $this->actingAs($rep1)
            ->delete(route('architects.destroy', $otherArchitect))
            ->assertForbidden();

        $this->assertDatabaseHas('architects', [
            'id' => $otherArchitect->id,
        ]);
    }
}

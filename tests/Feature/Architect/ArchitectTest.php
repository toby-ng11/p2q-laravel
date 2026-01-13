<?php

namespace Tests\Feature\Architect;

use App\Enums\UserRole;
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

    public function test_architect_cannot_be_created_if_user_role_is_too_low()
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);
        $lowRoleUser = User::factory()->create([
            'id' => 30,
            'user_role_id' => UserRole::GUEST,
        ]);

        $this->actingAs($rep)
            ->postJson('/architects', [
                'architect_name'    => 'Modern Designs Inc',
                'architect_rep_id'  => $lowRoleUser->id,
                'architect_type_id' => 1,
                'class_id'          => 'A',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['architect_rep_id']);
    }

    public function test_architect_can_be_created_if_rep_role_is_greater_than_3()
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);
        $highRoleUser = User::factory()->create([
            'id' => 30,
            'user_role_id' => UserRole::MANAGER,
        ]);

        $this->actingAs($rep)
            ->postJson('/architects', [
                'architect_name'    => 'Modern Designs Inc',
                'architect_rep_id'  => $highRoleUser->id,
                'architect_type_id' => 1,
                'class_id'          => 'A',
            ])
            ->assertValid();
    }
}

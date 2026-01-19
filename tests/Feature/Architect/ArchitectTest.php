<?php

namespace Tests\Feature\Architect;

use App\Enums\UserRole;
use App\Models\Architect;
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
        $lowRoleUser = $this->makeUserWithRole(UserRole::GUEST);

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

    public function test_architect_can_be_created_if_rep_role_is_manager_or_above()
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);
        $highRoleUser = $this->makeUserWithRole(UserRole::MANAGER);
        $architect = [
            'architect_name'    => 'Modern Designs Inc',
            'architect_rep_id'  => $highRoleUser->id,
            'architect_type_id' => 1,
            'class_id'          => 'A',
        ];

        $this->actingAs($rep)
            ->postJson('/architects', $architect)
            ->assertValid();

        $this->assertDatabaseHas('architects', $architect);
    }

    public function test_any_architect_can_be_deleted_if_rep_role_is_manager_or_above()
    {
        $highRoleUser = $this->makeUserWithRole(UserRole::MANAGER);
        $architect1 = Architect::factory()->create();
        $architect2 = Architect::factory()->create();
        $architectId1 = $architect1->id;
        $architectId2 = $architect2->id;

        $this->actingAs($highRoleUser)
            ->delete("/architects/$architectId1")
            ->assertValid()
            ->assertRedirectBack();

        $this->actingAs($highRoleUser)
            ->delete("/architects/$architectId2")
            ->assertValid()
            ->assertRedirectBack();

        $this->assertDatabaseMissing('architects', [
            'id' => $architectId1
        ]);

        $this->assertDatabaseMissing('architects', [
            'id' => $architectId2
        ]);
    }

    public function test_own_architect_can_be_deleted_if_rep_role_is_arch_rep()
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);
        $architect = Architect::factory()->create([
            'architect_rep_id' => $rep->id,
        ]);
        $architectId = $architect->id;

        $this->actingAs($rep)
            ->delete("/architects/$architectId")
            ->assertValid()
            ->assertRedirectBack();

        $this->assertDatabaseMissing('architects', [
            'id' => $architectId
        ]);
    }

    public function test_rep_cannot_delete_othere_rep_architects()
    {
        $rep1 = $this->makeUserWithRole(UserRole::ARCHREP);
        $rep2 = $this->makeUserWithRole(UserRole::ARCHREP);
        $otherArchitect = Architect::factory()->create([
            'architect_rep_id' => $rep2->id,
        ]);
        $otherArchitectId = $otherArchitect->id;

        $this->actingAs($rep1)
            ->delete("/architects/$otherArchitectId")
            ->assertValid()
            ->assertForbidden();

        $this->assertDatabaseHas('architects', [
            'id' => $otherArchitectId
        ]);
    }

    public function test_any_architect_address_can_be_created_if_rep_role_is_manager_or_above()
    {
        $highRoleUser = $this->makeUserWithRole(UserRole::MANAGER);
        $architect = Architect::factory()->create();
        $architectId = $architect->id;

        $this->actingAs($highRoleUser)
            ->postJson("/architects/$architectId/addresses", [
                'phys_address1' => '123 Test',
            ])
            ->assertValid()
            ->assertRedirectBack();

        $this->assertDatabaseHas('addresses', ['phys_address1' => '123 Test']);
    }

    public function test_own_architect_address_can_be_created_if_rep_role_is_arch_rep()
    {
        $rep = $this->makeUserWithRole(UserRole::ARCHREP);
        $architect = Architect::factory()->create(
            ['architect_rep_id' => $rep->id]
        );
        $architectId = $architect->id;

        $this->actingAs($rep)
            ->postJson("/architects/$architectId/addresses", [
                'phys_address1' => '123 Test',
            ])
            ->assertValid()
            ->assertRedirectBack();

        $this->assertDatabaseHas('addresses', ['phys_address1' => '123 Test']);
    }

    public function test_not_own_architect_address_cannot_be_created_if_rep_role_is_arch_rep()
    {
        $rep1 = $this->makeUserWithRole(UserRole::ARCHREP);
        $rep2 = $this->makeUserWithRole(UserRole::ARCHREP);
        $otherArchitect = Architect::factory()->create(
            ['architect_rep_id' => $rep2->id]
        );
        $architectId = $otherArchitect->id;

        $this->actingAs($rep1)
            ->postJson("/architects/$architectId/addresses", [
                'phys_address1' => '123 Test',
            ])
            ->assertValid()
            ->assertForbidden();

        $this->assertDatabaseMissing('addresses', [
            'phys_address1' => '123 Test',
        ]);
    }

    public function test_any_architect_address_can_be_deleted_if_rep_role_is_manager_or_above()
    {
        $highRoleUser = $this->makeUserWithRole(UserRole::MANAGER);
        $architect = Architect::factory()->create();
        $architectId = $architect->id;
        $addresses = $architect->addresses()->get()->toArray();

        foreach ($addresses as $address) {
            $addressId = $address['id'];
            $this->actingAs($highRoleUser)
                ->delete("/architects/$architectId/addresses/$addressId")
                ->assertValid()
                ->assertRedirectBack();

            $this->assertDatabaseMissing('addresses', [
                'id' => $address['id'],
            ]);
        }
    }

    public function test_address_cannot_be_modified_if_doesnt_belong_to_architect()
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);
        /** @var Architect $architect1 */
        $architect1 = Architect::factory()->create();
        /** @var Architect $architect2 */
        $architect2 = Architect::factory()->create();
        $architect2Id = $architect2->id;

        $addressOfArchitect1 = $architect1->addresses()->get()->first();
        $addressId = $addressOfArchitect1['id'];

        $this->actingAs($user)
            ->putJson("/architects/$architect2Id/addresses/$addressId", [
                'phys_address1' => '123 Test',
            ])
            ->assertNotFound();

        $this->actingAs($user)
            ->delete("/architects/$architect2Id/addresses/$addressId")
            ->assertNotFound();
    }
}

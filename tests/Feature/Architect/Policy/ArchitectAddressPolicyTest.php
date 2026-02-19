<?php

namespace Tests\Feature\Architect\Policy;

use App\Enums\UserRole;
use App\Models\Architect;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArchitectAddressPolicyTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithRole(UserRole $role): User
    {
        return User::factory()->create([
            'user_role_id' => $role,
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
            ->assertValid();

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
            ->assertValid();

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
        $user = $this->makeUserWithRole(UserRole::ADMIN);
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

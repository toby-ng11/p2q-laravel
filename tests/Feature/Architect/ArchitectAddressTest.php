<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Address;
use App\Models\Architect;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArchitectAddressTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithRole(UserRole $role): User
    {
        return User::factory()->create([
            'user_role_id' => $role,
        ]);
    }

    public function test_architect_address_can_be_created_successfully(): void
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);

        $architect = Architect::factory()->create([
            'architect_rep_id' => $user->id,
        ]);

        $address = [
            'phys_address1' => '123 Test',
        ];

        $this->actingAs($user)
            ->post(route('architects.addresses.store', $architect), $address)
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('addresses', $address);
    }

    public function test_architect_address_can_be_updated_successfully(): void
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);

        /** @var Architect */
        $architect = Architect::factory()->create([
            'architect_rep_id' => $user->id,
        ]);

        /** @var \Illuminate\Database\Eloquent\Collection<int, Address> */
        $addresses = $architect->addresses()->get(['id', 'phys_address1']);
        $address1 = $addresses->first();
        $address2 = $addresses->firstWhere('id', '!=', $address1->id);

        $editAddress = [
            'phys_address1' => 'New Address',
        ];

        $this->actingAs($user)
            ->put(route('architects.addresses.update', [$architect, $address1]), $editAddress)
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('addresses', $editAddress);
        $this->assertDatabaseHas('addresses', $address2->toArray());
    }

    public function test_architect_address_can_be_deleted_successfully(): void
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);

        /** @var Architect */
        $architect = Architect::factory()->create([
            'architect_rep_id' => $user->id,
        ]);

        /** @var Address */
        $address = $architect->addresses()->get()->first();

        $this->actingAs($user)
            ->delete(route('architects.addresses.destroy', [$architect, $address]))
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('addresses', $address->toArray());
    }
}

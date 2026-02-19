<?php

namespace Tests\Feature\Architect;

use App\Enums\UserRole;
use App\Models\Architect;
use App\Models\Specifier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpecifierTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithRole(UserRole $role): User
    {
        return User::factory()->create([
            'user_role_id' => $role,
        ]);
    }

    public function test_specifier_can_be_created_successfully(): void
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);
        $architect = Architect::factory()->create([
            'architect_rep_id' => $user->id,
        ]);

        $specifier = [
            'first_name' => 'Test',
            'last_name' => '',
            'job_title' => '',
            'email_address' => '',
            'central_phone_number' => '',
        ];

        $this->actingAs($user)
            ->post(route('architects.specifiers.store', $architect), $specifier)
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('specifiers', ['first_name' => 'Test']);
        $this->assertDatabaseHas('addresses', ['name' => 'Test']);
    }

    public function test_specifier_can_be_updated_successfully(): void
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);
        /** @var Architect $architect */
        $architect = Architect::factory()->create([
            'architect_rep_id' => $user->id,
        ]);

        /** @var Specifier $specifier */
        $specifier = $architect->specifiers()->get()->first();

        $editData = [
            'first_name' => 'New specifier name',
            'last_name' => $specifier->last_name,
            'job_title' => $specifier->job_title,
            'email_address' => $specifier->address->email_address,
            'central_phone_number' => $specifier->address->central_phone_number,
        ];

        $this->actingAs($user)
            ->put(
                route('architects.specifiers.update', [$architect, $specifier]),
                $editData
            )
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('specifiers', ['first_name' => 'New specifier name']);
        $this->assertDatabaseHas('addresses', ['name' => 'New specifier name' . ' ' . $specifier->last_name]);
    }

    public function test_specifier_can_be_deleted_successfully(): void
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);
        $architect = Architect::factory()->create([
            'architect_rep_id' => $user->id,
        ]);

        /** @var Specifier $specifier */
        $specifier = $architect->specifiers()->get()->first();
        $specifierId = $specifier->id;
        $specifierAddressId = $specifier->address->id;

        $this->actingAs($user)
            ->delete(route('architects.specifiers.destroy', [$architect, $specifier]))
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('specifiers', ['id' => $specifierId]);
        $this->assertDatabaseMissing('addresses', ['id' => $specifierAddressId]);
    }
}

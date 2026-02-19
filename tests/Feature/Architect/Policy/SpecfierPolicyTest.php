<?php

namespace Tests\Feature\Policy;

use App\Enums\UserRole;
use App\Models\Architect;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpecfierPolicyTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithRole(UserRole $role): User
    {
        return User::factory()->create([
            'user_role_id' => $role,
        ]);
    }

    public function test_specifier_cannot_be_modified_if_doesnt_belong_to_architect()
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);
        /** @var Architect $architect1 */
        $architect1 = Architect::factory()->create([
            'architect_rep_id' => $user->id,
        ]);
        /** @var Architect $architect2 */
        $architect2 = Architect::factory()->create([
            'architect_rep_id' => $user->id,
        ]);

        $specifierOfArchitect1 = $architect1->specifiers()->get()->first();

        $this->actingAs($user)
            ->put(route('architects.specifiers.update', [$architect2, $specifierOfArchitect1]), [
                'first_name' => 'Test',
            ])
            ->assertNotFound();

        $this->actingAs($user)
            ->delete(route('architects.specifiers.destroy', [$architect2, $specifierOfArchitect1]))
            ->assertNotFound();
    }
}

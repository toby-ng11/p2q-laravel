<?php

namespace Tests\Unit\Policies;

use App\Enums\UserRole;
use App\Models\Architect;
use App\Models\User;
use App\Policies\ArchitectPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchitectPolicyTest extends TestCase
{
    use RefreshDatabase;

    private ArchitectPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ArchitectPolicy();
    }

    private function user(UserRole $role): User
    {
        return User::factory()->create(['user_role_id' => $role]);
    }

    public function test_archrep_can_view_own_architect(): void
    {
        $rep = $this->user(UserRole::ARCHREP);
        $architect = Architect::factory()->create(['architect_rep_id' => $rep->id]);

        $this->assertTrue(
            $this->policy->view($rep, $architect)
        );
    }

    public function test_archrep_cannot_view_others_architect(): void
    {
        $rep = $this->user(UserRole::ARCHREP);
        $other = $this->user(UserRole::ARCHREP);

        $architect = Architect::factory()->create(['architect_rep_id' => $other->id]);

        $this->assertFalse(
            $this->policy->view($rep, $architect)
        );
    }

    public function test_sales_can_view_any_architect(): void
    {
        $sales = $this->user(UserRole::SALES);
        $architect = Architect::factory()->create();

        $this->assertTrue(
            $this->policy->view($sales, $architect)
        );
    }

    public function test_sales_cannot_update_architect(): void
    {
        $sales = $this->user(UserRole::SALES);
        $architect = Architect::factory()->create();

        $this->assertFalse(
            $this->policy->update($sales, $architect)
        );
    }

    public function test_manager_can_update_any_architect(): void
    {
        $manager = $this->user(UserRole::MANAGER);
        $architect = Architect::factory()->create();

        $this->assertTrue(
            $this->policy->update($manager, $architect)
        );
    }

    public function test_admin_can_do_anything(): void
    {
        $admin = $this->user(UserRole::ADMIN);
        $architect = Architect::factory()->create();

        $this->assertTrue($this->policy->delete($admin, $architect));
        $this->assertTrue($this->policy->update($admin, $architect));
        $this->assertTrue($this->policy->view($admin, $architect));
    }
}

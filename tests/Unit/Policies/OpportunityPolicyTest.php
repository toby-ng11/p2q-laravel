<?php

namespace Tests\Unit\Policies;

use App\Enums\UserRole;
use App\Models\Opportunity;
use App\Models\User;
use App\Policies\OpportunityPolicy;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OpportunityPolicyTest extends TestCase
{
    private OpportunityPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new OpportunityPolicy();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function makeUser(UserRole $role): User
    {
        $user = Mockery::mock(User::class);
        $user->makePartial();
        $user->id = 1;
        $user->user_role_id = $role;
        $user->shouldReceive('isAdministrator')->andReturn($role === UserRole::ADMIN);
        $user->shouldReceive('isManagerOrAbove')->andReturn($role->atLeast(UserRole::MANAGER));
        return $user;
    }

    private function makeOpportunity(int $createdBy, int $archRepId): Opportunity
    {
        $architect = (object) ['architect_rep_id' => $archRepId];

        $opportunity = Mockery::mock(Opportunity::class);
        $opportunity->makePartial();
        $opportunity->created_by = $createdBy;
        $opportunity->architect = $architect;

        return $opportunity;
    }

    public static function writeAbilityProvider(): array
    {
        return [
            'update'      => ['update'],
            'delete'      => ['delete'],
            'restore'     => ['restore'],
            'forceDelete' => ['forceDelete'],
        ];
    }

    public function test_before_returns_true_for_admin(): void
    {
        $admin = $this->makeUser(UserRole::ADMIN);

        $this->assertTrue($this->policy->before($admin, 'view'));
        $this->assertTrue($this->policy->before($admin, 'delete'));
    }

    public function test_before_returns_null_for_non_admin(): void
    {
        $user = $this->makeUser(UserRole::ARCHREP);

        $this->assertNull($this->policy->before($user, 'view'));
    }

    public function test_manager_can_view_any_opportunity(): void
    {
        $manager = $this->makeUser(UserRole::MANAGER);
        $opportunity = $this->makeOpportunity(createdBy: 99, archRepId: 99);

        $this->assertTrue($this->policy->view($manager, $opportunity));
    }

    public function test_sales_can_view_any_opportunity(): void
    {
        $sales = $this->makeUser(UserRole::SALES);
        $opportunity = $this->makeOpportunity(createdBy: 99, archRepId: 99);

        $this->assertTrue($this->policy->view($sales, $opportunity));
    }

    public function test_archrep_can_view_opportunity_they_created(): void
    {
        $user = $this->makeUser(UserRole::ARCHREP);
        $opportunity = $this->makeOpportunity(createdBy: $user->id, archRepId: 99);

        $this->assertTrue($this->policy->view($user, $opportunity));
    }

    public function test_archrep_can_view_opportunity_they_are_assigned_to(): void
    {
        $user = $this->makeUser(UserRole::ARCHREP);
        $opportunity = $this->makeOpportunity(createdBy: 99, archRepId: $user->id);

        $this->assertTrue($this->policy->view($user, $opportunity));
    }

    public function test_archrep_cannot_view_unrelated_opportunity(): void
    {
        $user = $this->makeUser(UserRole::ARCHREP);
        $opportunity = $this->makeOpportunity(createdBy: 99, archRepId: 99);

        $this->assertFalse($this->policy->view($user, $opportunity));
    }

    public function test_guest_cannot_view_opportunity(): void
    {
        $guest = $this->makeUser(UserRole::GUEST);
        $opportunity = $this->makeOpportunity(createdBy: 99, archRepId: 99);

        $this->assertFalse($this->policy->view($guest, $opportunity));
    }

    public function test_manager_can_view_any(): void
    {
        $this->assertTrue($this->policy->viewAny($this->makeUser(UserRole::MANAGER)));
    }

    public function test_sales_can_view_any(): void
    {
        $this->assertTrue($this->policy->viewAny($this->makeUser(UserRole::SALES)));
    }

    public function test_archrep_cannot_view_any(): void
    {
        $this->assertFalse($this->policy->viewAny($this->makeUser(UserRole::ARCHREP)));
    }

    #[DataProvider('writeAbilityProvider')]
    public function test_manager_can_always_write(string $ability): void
    {
        $manager = $this->makeUser(UserRole::MANAGER);
        $opportunity = $this->makeOpportunity(createdBy: 99, archRepId: 99);

        $this->assertTrue($this->policy->$ability($manager, $opportunity));
    }

    #[DataProvider('writeAbilityProvider')]
    public function test_archrep_owner_can_write(string $ability): void
    {
        $user = $this->makeUser(UserRole::ARCHREP);
        $opportunity = $this->makeOpportunity(createdBy: $user->id, archRepId: 99);

        $this->assertTrue($this->policy->$ability($user, $opportunity));
    }

    #[DataProvider('writeAbilityProvider')]
    public function test_archrep_assigned_can_write(string $ability): void
    {
        $user = $this->makeUser(UserRole::ARCHREP);
        $opportunity = $this->makeOpportunity(createdBy: 99, archRepId: $user->id);

        $this->assertTrue($this->policy->$ability($user, $opportunity));
    }

    #[DataProvider('writeAbilityProvider')]
    public function test_archrep_unrelated_cannot_write(string $ability): void
    {
        $user = $this->makeUser(UserRole::ARCHREP);
        $opportunity = $this->makeOpportunity(createdBy: 99, archRepId: 99);

        $this->assertFalse($this->policy->$ability($user, $opportunity));
    }

    #[DataProvider('writeAbilityProvider')]
    public function test_sales_cannot_write(string $ability): void
    {
        $sales = $this->makeUser(UserRole::SALES);
        $opportunity = $this->makeOpportunity(createdBy: 99, archRepId: 99);

        $this->assertFalse($this->policy->$ability($sales, $opportunity));
    }

    #[DataProvider('writeAbilityProvider')]
    public function test_guest_cannot_write(string $ability): void
    {
        $guest = $this->makeUser(UserRole::GUEST);
        $opportunity = $this->makeOpportunity(createdBy: 99, archRepId: 99);

        $this->assertFalse($this->policy->$ability($guest, $opportunity));
    }

    public function test_manager_can_create(): void
    {
        $manager = $this->makeUser(UserRole::MANAGER);

        $this->assertTrue($this->policy->create($manager));
    }

    public function test_archrep_can_create(): void
    {
        $user = $this->makeUser(UserRole::ARCHREP);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_sales_cannot_create(): void
    {
        $user = $this->makeUser(UserRole::SALES);

        $this->assertFalse($this->policy->create($user));
    }

    public function test_guest_cannot_create(): void
    {
        $guest = $this->makeUser(UserRole::GUEST);

        $this->assertFalse($this->policy->create($guest));
    }
}

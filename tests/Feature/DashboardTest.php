<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithRole(UserRole $role): User
    {
        return User::factory()->create([
            'user_role_id' => $role,
        ]);
    }

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->get(route('dashboard.home'))->assertRedirect(route('login'));
    }

    public function test_authenticated_guest_can_visit_the_dashboard()
    {
        $user = $this->makeUserWithRole(UserRole::GUEST);
        $this->actingAs($user);

        $this->get(route('dashboard.home'))->assertOk();
        $this->get(route('dashboard.project'))->assertRedirectToRoute('dashboard.home');
        $this->get(route('dashboard.quote'))->assertRedirectToRoute('dashboard.home');
        $this->get(route('dashboard.opportunity'))->assertRedirectToRoute('dashboard.home');
        $this->get(route('dashboard.architect'))->assertRedirectToRoute('dashboard.home');
    }

    public function test_authenticated_arch_rep_can_visit_the_dashboard()
    {
        $user = $this->makeUserWithRole(UserRole::ARCHREP);
        $this->actingAs($user);

        $this->get(route('dashboard.home'))->assertOk();
        $this->get(route('dashboard.project'))->assertOk();
        $this->get(route('dashboard.quote'))->assertOk();
        $this->get(route('dashboard.opportunity'))->assertOk();
        $this->get(route('dashboard.architect'))->assertOk();
    }
}

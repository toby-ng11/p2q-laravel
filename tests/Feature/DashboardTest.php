<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::factory()->create();
    }

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->get(route('dashboard.home'))->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard()
    {
        $user = $this->user();
        $this->actingAs($user);

        $this->get(route('dashboard.home'))->assertOk();
        $this->get(route('dashboard.project'))->assertOk();
        $this->get(route('dashboard.quote'))->assertOk();
        $this->get(route('dashboard.opportunity'))->assertOk();
        $this->get(route('dashboard.architect'))->assertOk();
    }
}

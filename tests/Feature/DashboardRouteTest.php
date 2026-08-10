<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\AdminUser\Models\User;
use Tests\TestCase;

class DashboardRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_dashboard_page(): void
    {
        $user = User::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($user)->get('/portal/dashboard');
        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/portal/dashboard');
        $response->assertRedirect('/login');
    }
}

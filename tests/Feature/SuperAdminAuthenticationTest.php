<?php

namespace Tests\Feature;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_super_admin_can_sign_in_with_email_and_password(): void
    {
        SuperAdmin::create([
            'name' => 'Master Admin',
            'email' => 'master@example.com',
            'password' => 'Strong-password-123',
        ]);

        $response = $this->post(route('super-admin.login.post'), [
            'email' => 'master@example.com',
            'password' => 'Strong-password-123',
        ]);

        $this->assertAuthenticated('super_admin');
        $response->assertRedirect(route('super-admin.dashboard'));
    }

    public function test_first_account_setup_is_unavailable_without_a_configured_setup_code(): void
    {
        config(['services.super_admin.setup_code' => null]);

        $this->get(route('super-admin.signup'))->assertNotFound();
    }
}

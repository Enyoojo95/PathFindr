<?php

namespace Tests\Feature;

use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminAdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_suspend_and_restore_a_school_admin(): void
    {
        $master = SuperAdmin::create([
            'name' => 'Master Admin',
            'email' => 'master@example.com',
            'password' => 'Strong-password-123',
        ]);
        $schoolAdmin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'school_name' => 'Test School',
        ]);

        $this->actingAs($master, 'super_admin')
            ->post(route('super-admin.admins.suspend', $schoolAdmin))
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $schoolAdmin->id, 'is_suspended' => true]);

        $this->actingAs($schoolAdmin->fresh(), 'web')
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($master, 'super_admin')
            ->post(route('super-admin.admins.suspend', $schoolAdmin))
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $schoolAdmin->id, 'is_suspended' => false]);
    }

    public function test_super_admin_cannot_delete_a_student_through_admin_management(): void
    {
        $master = SuperAdmin::create([
            'name' => 'Master Admin',
            'email' => 'master@example.com',
            'password' => 'Strong-password-123',
        ]);
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);

        $this->actingAs($master, 'super_admin')
            ->delete(route('super-admin.admins.destroy', $student))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $student->id]);
    }
}
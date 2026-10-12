<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_uses_registered_students_and_distinct_represented_schools(): void
    {
        User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'school_name' => 'Crystalfield Academy',
        ]);
        User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'school_name' => ' crystalfield academy ',
        ]);
        User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'school_name' => 'Northview School',
        ]);
        User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertViewHas('totalStudents', 3);
        $response->assertViewHas('totalSchools', 2);
        $response->assertSee('Registered Students');
        $response->assertSee('Schools Represented');
    }
}
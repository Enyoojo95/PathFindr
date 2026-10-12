<?php

namespace Tests\Feature;

use App\Models\TestResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_download_a_pdf_report_with_recommendations(): void
    {
        $user = User::factory()->create();
        $result = TestResult::create([
            'user_id' => $user->id,
            'numerical_score' => 85,
            'verbal_score' => 65,
            'spatial_score' => 80,
            'interest_score' => 70,
            'total_score' => 75,
            'recommended_combination' => 'Science',
            'careers' => ['Software Engineer', 'Civil Engineer', 'Architect', 'Data Scientist', 'Research Scientist'],
            'universities' => [
                ['name' => 'University of Lagos', 'course' => 'Computer Science'],
                ['name' => 'University of Ibadan', 'course' => 'Biochemistry'],
                ['name' => 'University of Nigeria, Nsukka', 'course' => 'Microbiology'],
                ['name' => 'Federal University of Technology, Akure', 'course' => 'Electrical & Electronics Engineering'],
                ['name' => 'Federal University of Technology, Minna', 'course' => 'Computer Engineering'],
                ['name' => 'Federal University of Technology, Owerri', 'course' => 'Mechanical Engineering'],
                ['name' => 'University of Ilorin', 'course' => 'Civil Engineering'],
                ['name' => 'University of Benin', 'course' => 'Chemical Engineering'],
                ['name' => 'University of Port Harcourt', 'course' => 'Petroleum Engineering'],
            ],
            'motivational_message' => 'Your strong numerical and spatial reasoning opens up exciting science and engineering possibilities. Keep building your skills through practice.',
        ]);

        $response = $this->actingAs($user)->get(route('exam.result.pdf', $result));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}

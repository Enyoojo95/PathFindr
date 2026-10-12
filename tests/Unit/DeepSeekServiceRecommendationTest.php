<?php

namespace Tests\Unit;

use App\Services\DeepSeekService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DeepSeekServiceRecommendationTest extends TestCase
{
    public function test_fallback_recommendations_change_for_the_same_pathway_when_the_student_profile_changes(): void
    {
        $service = app(DeepSeekService::class);

        $mathHeavyScience = $service->getRecommendation(92, 55, 74, 80);
        $visualHeavyScience = $service->getRecommendation(68, 52, 92, 80);

        $this->assertSame('Science', $mathHeavyScience['combination']);
        $this->assertSame('Science', $visualHeavyScience['combination']);
        $this->assertNotSame($mathHeavyScience['careers'], $visualHeavyScience['careers']);
        $this->assertNotSame($mathHeavyScience['universities'], $visualHeavyScience['universities']);
        $this->assertCount(10, $mathHeavyScience['universities']);
    }

    public function test_average_or_lower_scores_do_not_receive_career_or_university_recommendations(): void
    {
        Http::fake();

        $recommendation = app(DeepSeekService::class)->getRecommendation(50, 50, 50, 50);

        $this->assertSame([], $recommendation['careers']);
        $this->assertSame([], $recommendation['universities']);
        Http::assertNothingSent();
    }

    public function test_above_average_science_profile_receives_a_long_university_shortlist(): void
    {
        $universities = collect(range(1, 10))->map(fn ($number) => [
            'name' => "Nigerian University {$number}",
            'course' => 'Engineering',
        ])->all();
        $content = json_encode([
            'combination' => 'Science',
            'careers' => ['Engineer'],
            'universities' => $universities,
            'message' => 'Keep learning.',
        ]);

        config(['services.deepseek.key' => 'test-key']);
        Http::fake([
            'api.deepseek.com/*' => Http::response([
                'choices' => [['message' => ['content' => $content]]],
            ]),
        ]);

        $recommendation = app(DeepSeekService::class)->getRecommendation(85, 65, 80, 70);

        $this->assertCount(10, $recommendation['universities']);
        $this->assertSame('Science', $recommendation['combination']);
    }
}

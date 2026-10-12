<?php

namespace Tests\Feature;

use App\Http\Controllers\ExamController;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamQuestionPoolTest extends TestCase
{
    use RefreshDatabase;

    public function test_build_fresh_question_ids_uses_seeded_sections_and_keeps_the_pool_unique(): void
    {
        Question::query()->delete();

        foreach (['numerical', 'verbal', 'spatial', 'interest'] as $category) {
            for ($i = 1; $i <= 40; $i++) {
                Question::create([
                    'category'       => $category,
                    'question_text'  => "Sample {$category} question {$i}",
                    'option_a'       => 'A',
                    'option_b'       => 'B',
                    'option_c'       => 'C',
                    'option_d'       => 'D',
                    'correct_answer' => 'A',
                ]);
            }
        }

        $controller = new ExamController();
        $ids = $controller->buildFreshQuestionIds();

        $this->assertCount(30, $ids);
        $this->assertCount(count(array_unique($ids)), $ids);

        $categoryCounts = Question::whereIn('id', $ids)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        ksort($categoryCounts);
        $this->assertSame(['interest' => 7, 'numerical' => 8, 'spatial' => 7, 'verbal' => 8], $categoryCounts);

        $secondPool = $controller->buildFreshQuestionIds();
        $this->assertNotSame($ids, $secondPool);
    }
}

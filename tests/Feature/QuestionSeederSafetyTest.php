<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\User;
use Database\Seeders\QuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionSeederSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_running_the_question_seeder_preserves_existing_questions_and_student_answers(): void
    {
        $question = Question::create([
            'category' => 'numerical',
            'question_text' => 'Existing seeded question',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_answer' => 'A',
        ]);
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $answer = StudentAnswer::create([
            'user_id' => $student->id,
            'question_id' => $question->id,
            'selected_option' => 'A',
            'is_correct' => true,
        ]);

        app(QuestionSeeder::class)->run();

        $this->assertDatabaseHas('questions', ['id' => $question->id]);
        $this->assertDatabaseHas('student_answers', ['id' => $answer->id]);
    }
}
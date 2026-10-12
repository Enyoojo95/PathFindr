<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::query();
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        $questions = $query->orderBy('category')->orderBy('id')->paginate(20)->withQueryString();
        return view('super-admin.questions.index', compact('questions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'       => ['required', 'in:numerical,verbal,spatial,interest'],
            'question_text'  => ['required', 'string'],
            'option_a'       => ['required', 'string'],
            'option_b'       => ['required', 'string'],
            'option_c'       => ['required', 'string'],
            'option_d'       => ['required', 'string'],
            'correct_answer' => ['nullable', 'in:A,B,C,D'],
        ]);

        Question::create($request->only(['category','question_text','option_a','option_b','option_c','option_d','correct_answer']));
        return back()->with('success', 'Question added.');
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'category'       => ['required', 'in:numerical,verbal,spatial,interest'],
            'question_text'  => ['required', 'string'],
            'option_a'       => ['required', 'string'],
            'option_b'       => ['required', 'string'],
            'option_c'       => ['required', 'string'],
            'option_d'       => ['required', 'string'],
            'correct_answer' => ['nullable', 'in:A,B,C,D'],
        ]);

        $question->update($request->only(['category','question_text','option_a','option_b','option_c','option_d','correct_answer']));
        return back()->with('success', 'Question updated.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Question deleted.');
    }
}

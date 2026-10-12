<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::orderBy('category')->orderBy('id')->paginate(20);
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('admin.questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'       => ['required', 'in:numerical,verbal,spatial,interest'],
            'question_text'  => ['required', 'string'],
            'option_a'       => ['required', 'string', 'max:255'],
            'option_b'       => ['required', 'string', 'max:255'],
            'option_c'       => ['required', 'string', 'max:255'],
            'option_d'       => ['required', 'string', 'max:255'],
            'correct_answer' => ['nullable', 'in:A,B,C,D'],
        ]);

        Question::create($request->only([
            'category', 'question_text',
            'option_a', 'option_b', 'option_c', 'option_d',
            'correct_answer',
        ]));

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question added successfully.');
    }

    public function edit(Question $question)
    {
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'category'       => ['required', 'in:numerical,verbal,spatial,interest'],
            'question_text'  => ['required', 'string'],
            'option_a'       => ['required', 'string', 'max:255'],
            'option_b'       => ['required', 'string', 'max:255'],
            'option_c'       => ['required', 'string', 'max:255'],
            'option_d'       => ['required', 'string', 'max:255'],
            'correct_answer' => ['nullable', 'in:A,B,C,D'],
        ]);

        $question->update($request->only([
            'category', 'question_text',
            'option_a', 'option_b', 'option_c', 'option_d',
            'correct_answer',
        ]));

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question updated.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Question deleted.');
    }
}

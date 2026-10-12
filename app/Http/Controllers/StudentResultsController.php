<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\TestResult;
use Illuminate\Support\Facades\Auth;

class StudentResultsController extends Controller
{
    public function results()
    {
        $user    = Auth::user();
        $results = TestResult::where('user_id', $user->id)->latest()->get();

        // Map each result to its exam session (keyed by result id)
        $sessions = ExamSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->get()
            ->keyBy('id'); // we'll match by position since result↔session aren't linked by FK

        // Build a map: result_id => session (matched by order — each completed session = one result)
        $sessionList  = ExamSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        $sessionMap = collect();
        foreach ($results as $i => $result) {
            $sessionMap->put($result->id, $sessionList->get($i));
        }

        return view('student.results', [
            'results'  => $results,
            'sessions' => $sessionMap,
        ]);
    }

    public function reports()
    {
        $results = TestResult::where('user_id', Auth::id())->latest()->get();

        return view('student.reports', compact('results'));
    }
}

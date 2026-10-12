<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\TestResult;
use App\Models\ExamSession;
use App\Models\StudentAnswer;
use App\Services\DeepSeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    protected function ensureQuestionBankExists(): void
    {
        if (Question::count() > 0) {
            return;
        }

        app(\Database\Seeders\QuestionSeeder::class)->run();
    }

    public function start()
    {
        $user = Auth::user();

        $this->ensureQuestionBankExists();

        if (TestResult::where('user_id', $user->id)->exists()) {
            return redirect()->route('exam.result');
        }

        $liveSession = ExamSession::where('user_id', $user->id)
            ->whereIn('status', ['active', 'paused'])
            ->latest()
            ->first();

        $shouldResume = $liveSession
            && $liveSession->last_heartbeat
            && $liveSession->last_heartbeat->gt(now()->subMinutes(15))
            && !empty($liveSession->question_ids)
            && count(array_unique(array_map('intval', $liveSession->question_ids ?? []))) >= 20;

        if ($shouldResume) {
            $examSession = $liveSession;
        } else {
            ExamSession::where('user_id', $user->id)->whereIn('status', ['active', 'paused', 'abandoned'])->delete();

            $questionIds = $this->buildFreshQuestionIds();

            if (empty($questionIds)) {
                return back()->with('error', 'No exam questions available yet. Please contact your administrator.');
            }

            $examSession = ExamSession::create([
                'user_id'          => $user->id,
                'school_name'      => $user->school_name,
                'question_ids'     => $questionIds,
                'status'           => 'active',
                'current_question' => 0,
                'time_remaining'   => 1800,
                'started_at'       => now(),
                'last_heartbeat'   => now(),
            ]);
        }

        $storedIds = array_values(array_unique(array_map('intval', $examSession->question_ids ?? [])));

        if (count($storedIds) < 20 || count($storedIds) !== count(array_unique($storedIds))) {
            $storedIds = $this->buildFreshQuestionIds();
            $examSession->update(['question_ids' => $storedIds]);
        }

        $questions = Question::whereIn('id', $storedIds)
            ->get()
            ->sortBy(fn($q) => array_search($q->id, $storedIds, true))
            ->values();

        $savedAnswers = StudentAnswer::where('user_id', $user->id)
            ->whereIn('question_id', $storedIds)
            ->get()
            ->mapWithKeys(fn($answer) => [$answer->question_id => $answer->selected_option])
            ->toArray();

        // Also clear any stale PHP session data
        session()->forget('exam_questions');

        return view('exam.start', compact('questions', 'examSession', 'savedAnswers'));
    }

    public function normalizeQuestionIds(array $questionIds): array
    {
        $questionIds = array_values(array_unique(array_map('intval', $questionIds)));
        shuffle($questionIds);

        return $questionIds;
    }

    public function buildFreshQuestionIds(): array
    {
        $sectionCounts = [
            'numerical' => 8,
            'verbal'    => 8,
            'spatial'   => 7,
            'interest'  => 7,
        ];

        $allIds = [];

        foreach ($sectionCounts as $category => $count) {
            $categoryIds = Question::where('category', $category)
                ->pluck('id')
                ->all();

            if (empty($categoryIds)) {
                continue;
            }

            shuffle($categoryIds);
            $selected = array_slice($categoryIds, 0, max(0, $count));
            $allIds = array_merge($allIds, $selected);
        }

        $allIds = array_values(array_unique(array_map('intval', $allIds)));
        shuffle($allIds);

        return $allIds;
    }

    public function saveAnswer(Request $request)
    {
        $request->validate([
            'question_id' => ['required', 'integer', 'exists:questions,id'],
            'selected_option' => ['required', 'string', 'in:A,B,C,D'],
        ]);

        $user = Auth::user();
        $examSession = ExamSession::where('user_id', $user->id)
            ->whereIn('status', ['active', 'paused'])
            ->latest()
            ->first();

        if (!$examSession) {
            return response()->json(['message' => 'No active exam session.'], 404);
        }

        $question = Question::findOrFail($request->question_id);

        if (!in_array($question->id, $examSession->question_ids ?? [], true)) {
            return response()->json(['message' => 'Question is not part of this exam session.'], 422);
        }

        StudentAnswer::updateOrCreate(
            [
                'user_id' => $user->id,
                'question_id' => $question->id,
            ],
            [
                'selected_option' => strtoupper($request->selected_option),
                'is_correct' => strtoupper($request->selected_option) === strtoupper($question->correct_answer ?? ''),
            ]
        );

        return response()->json(['status' => 'saved']);
    }

    /** Heartbeat — student pings every 5s to stay "online" */
    public function heartbeat(Request $request)
    {
        $session = ExamSession::where('user_id', Auth::id())
            ->whereIn('status', ['active', 'paused'])
            ->latest()->first();

        if (!$session) {
            return response()->json(['status' => 'no_session'], 404);
        }

        // Update heartbeat & save time/question progress
        $session->last_heartbeat   = now();
        $session->current_question = $request->input('current_question', $session->current_question);
        $session->time_remaining   = $request->input('time_remaining', $session->time_remaining);
        if ($session->status === 'active') {
            $session->save();
        }

        return response()->json([
            'status'        => $session->status,
            'pause_message' => $session->pause_message,
            'paused_by'     => $session->paused_by,
        ]);
    }

    /** Admin pauses/resumes an exam session */
    public function adminPause(Request $request, $userId)
    {
        // Only admins/super admins
        $admin = Auth::user();
        abort_if(!$admin->hasAdminAccess(), 403);

        $session = ExamSession::where('user_id', $userId)
            ->where('status', 'active')
            ->latest()->first();

        abort_if(!$session, 404, 'No active exam session for this student.');

        $session->status        = 'paused';
        $session->paused_at     = now();
        $session->paused_by     = $admin->name;
        $session->pause_message = $request->input('message', 'Your exam has been paused by your supervisor. Please wait.');
        $session->save();

        return response()->json(['ok' => true]);
    }

    /** Admin resumes a paused session */
    public function adminResume($userId)
    {
        $admin = Auth::user();
        abort_if(!$admin->hasAdminAccess(), 403);

        $session = ExamSession::where('user_id', $userId)
            ->where('status', 'paused')
            ->latest()->first();

        abort_if(!$session, 404);

        $session->status        = 'active';
        $session->paused_at     = null;
        $session->paused_by     = null;
        $session->pause_message = null;
        $session->save();

        return response()->json(['ok' => true]);
    }

    /** Active exams list for admin dashboard (JSON) */
    public function activeExams()
    {
        $admin = Auth::user();
        abort_if(!$admin->hasAdminAccess(), 403);

        $query = ExamSession::with('user')
            ->whereIn('status', ['active', 'paused'])
            ->where('last_heartbeat', '>=', now()->subSeconds(20));

        // Scope to admin's school if not super admin
        if (!$admin->isSuperAdmin() && $admin->school_name) {
            $query->whereHas('user', fn($q) =>
                $q->whereRaw('LOWER(school_name) = ?', [strtolower($admin->school_name)])
            );
        }

        $sessions = $query->get()->map(fn($s) => [
            'id'               => $s->id,
            'user_id'          => $s->user_id,
            'name'             => $s->user->name,
            'school'           => $s->school_name ?? $s->user->school_name ?? '—',
            'status'           => $s->status,
            'current_question' => $s->current_question,
            'time_remaining'   => $s->time_remaining,
            'online'           => $s->isOnline(),
        ]);

        return response()->json($sessions);
    }

    public function submit(Request $request)
    {
        $user = Auth::user();

        // Get question IDs from the active exam session (not PHP session)
        $examSession = ExamSession::where('user_id', $user->id)
            ->whereIn('status', ['active', 'paused'])
            ->latest()->first();

        $questionIds = $examSession?->question_ids ?? session('exam_questions', []);

        if (empty($questionIds)) {
            return redirect()->route('exam.start');
        }

        $answers   = $request->input('answers', []);
        $questions = Question::whereIn('id', $questionIds)->get()->keyBy('id');

        $scores = [
            'numerical' => ['correct' => 0, 'total' => 0],
            'verbal'    => ['correct' => 0, 'total' => 0],
            'spatial'   => ['correct' => 0, 'total' => 0],
            'interest'  => ['answered' => 0, 'total' => 0],
        ];

        foreach ($questions as $id => $q) {
            $given = $answers[$id] ?? null;
            if ($q->category === 'interest') {
                $scores['interest']['total']++;
                if ($given) $scores['interest']['answered']++;
            } else {
                $scores[$q->category]['total']++;
                if ($given && strtoupper($given) === $q->correct_answer) {
                    $scores[$q->category]['correct']++;
                }
            }
        }

        $numScore     = $scores['numerical']['total'] > 0 ? round(($scores['numerical']['correct'] / $scores['numerical']['total']) * 100, 1) : 0;
        $verbalScore  = $scores['verbal']['total']    > 0 ? round(($scores['verbal']['correct']    / $scores['verbal']['total'])    * 100, 1) : 0;
        $spatialScore = $scores['spatial']['total']   > 0 ? round(($scores['spatial']['correct']   / $scores['spatial']['total'])   * 100, 1) : 0;
        $interestScore= $scores['interest']['total']  > 0 ? round(($scores['interest']['answered'] / $scores['interest']['total'])  * 100, 1) : 100;
        $totalScore   = round(($numScore + $verbalScore + $spatialScore + $interestScore) / 4, 1);

        $combo = null;
        $totalAnswered = count(array_filter($answers, fn($a) => !empty($a)));

        if ($totalAnswered >= 10) {
            $combo = 'Arts';
            if ($numScore >= 60 && $spatialScore >= 60)      $combo = 'Science';
            elseif ($numScore >= 50 && $verbalScore >= 50)   $combo = 'Commercial';
            elseif ($verbalScore >= 60)                      $combo = 'Arts';
        }

        // ── Call DeepSeek AI for personalised recommendations ──────────
        $ai = app(DeepSeekService::class)->getRecommendation(
            $numScore, $verbalScore, $spatialScore, $interestScore
        );

        // Override the rule-based combo with the AI recommendation when available
        $finalCombo = $totalAnswered >= 10
            ? ($ai['combination'] ?? $combo)
            : $combo;

        TestResult::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'numerical_score'         => $numScore,
                'verbal_score'            => $verbalScore,
                'spatial_score'           => $spatialScore,
                'interest_score'          => $interestScore,
                'total_score'             => $totalScore,
                'recommended_combination' => $finalCombo,
                'careers'                 => $ai['careers']      ?? null,
                'universities'            => $ai['universities'] ?? null,
                'motivational_message'    => $ai['message']      ?? null,
            ]
        );

        // Mark exam session completed
        ExamSession::where('user_id', Auth::id())
            ->whereIn('status', ['active', 'paused'])
            ->update(['status' => 'completed']);

        session()->forget('exam_questions');

        return redirect()->route('exam.result');    }

    public function result()
    {
        $result = TestResult::where('user_id', Auth::id())->latest()->first();
        if (!$result) return redirect()->route('exam.start');
        return view('exam.result', compact('result'));
    }
}

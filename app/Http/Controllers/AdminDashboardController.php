<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin        = Auth::user();
        $isSuperAdmin = $admin->isSuperAdmin();
        $schoolName   = $admin->school_name;

        // ── Base student query ─────────────────────────────────────
        $studentQuery = User::where('role', User::ROLE_STUDENT);

        // School admins MUST be scoped — if somehow school_name is null, show nothing
        if (!$isSuperAdmin) {
            if ($schoolName) {
                $studentQuery->whereRaw('LOWER(school_name) = ?', [strtolower($schoolName)]);
            } else {
                // No school assigned — show empty to prevent data leaks
                $studentQuery->whereRaw('1 = 0');
            }
        }

        // ── Pull student IDs once for all sub-queries ──────────────
        $studentIds = (clone $studentQuery)->pluck('id');

        // ── Stats ──────────────────────────────────────────────────
        $totalStudents = $studentIds->count();

        // Each user can only have one TestResult (updateOrCreate), so count() == distinct count
        $testsCompleted = TestResult::whereIn('user_id', $studentIds)->count();

        $avgRaw   = TestResult::whereIn('user_id', $studentIds)
            ->whereNotNull('total_score')
            ->avg('total_score');
        $avgScore = $avgRaw !== null ? round((float) $avgRaw, 1) : null;

        $newThisWeek = (clone $studentQuery)
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();

        $testsToday = TestResult::whereIn('user_id', $studentIds)
            ->whereDate('updated_at', today())
            ->count();

        $completionRate = $totalStudents > 0
            ? round(($testsCompleted / $totalStudents) * 100, 1)
            : 0;

        // ── Recent students (latest 8) ─────────────────────────────
        $recentStudents = (clone $studentQuery)
            ->with([
                'student',
                'testResults' => fn($q) => $q->latest()->limit(1),
            ])
            ->latest()
            ->limit(8)
            ->get()
            ->map(function ($user) {
                $result = $user->testResults->first();
                return [
                    'name'   => $user->name,
                    'email'  => $user->email,
                    'avatar' => $user->avatarUrl(),
                    'school' => $user->school_name ?? optional($user->student)->school ?? '—',
                    'status' => $result ? 'Completed' : 'Pending',
                    'score'  => $result ? round($result->total_score, 1) . '%' : '—',
                    'combo'  => $result?->recommended_combination,
                    'joined' => $user->created_at->format('d M Y'),
                ];
            });

        // ── Combination breakdown ──────────────────────────────────
        $combinations = TestResult::whereIn('user_id', $studentIds)
            ->whereNotNull('recommended_combination')
            ->selectRaw('recommended_combination, COUNT(*) as total')
            ->groupBy('recommended_combination')
            ->pluck('total', 'recommended_combination')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalStudents',
            'testsCompleted',
            'avgScore',
            'newThisWeek',
            'testsToday',
            'completionRate',
            'recentStudents',
            'combinations',
            'isSuperAdmin',
            'schoolName'
        ));
    }
}

<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TestResult;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $bySchool = User::where('role', 'student')
            ->whereNotNull('school_name')
            ->selectRaw('school_name, COUNT(*) as students')
            ->groupBy('school_name')
            ->orderByDesc('students')
            ->limit(10)
            ->get();

        $byCombo = TestResult::whereNotNull('recommended_combination')
            ->selectRaw('recommended_combination, COUNT(*) as total')
            ->groupBy('recommended_combination')
            ->get();

        $avgBySchool = TestResult::join('users', 'test_results.user_id', '=', 'users.id')
            ->whereNotNull('users.school_name')
            ->selectRaw('users.school_name, ROUND(AVG(test_results.total_score),1) as avg_score, COUNT(*) as count')
            ->groupBy('users.school_name')
            ->orderByDesc('avg_score')
            ->get();

        return view('super-admin.analytics.index', compact('bySchool', 'byCombo', 'avgBySchool'));
    }

    public function download(Request $request)
    {
        $students = User::where('role', 'student')
            ->with(['testResults' => fn($q) => $q->latest()->limit(1)])
            ->when($request->school, fn($q) => $q->where('school_name', $request->school))
            ->get();

        $rows[] = ['Name', 'Email', 'School', 'Status', 'Score', 'Combination', 'Registered'];
        foreach ($students as $u) {
            $r = $u->testResults->first();
            $rows[] = [$u->name, $u->email, $u->school_name ?? '—',
                $r ? 'Completed' : 'Pending',
                $r ? round($r->total_score, 1) : '—',
                $r ? $r->recommended_combination : '—',
                $u->created_at->format('d M Y')];
        }

        $filename = 'pathfindr-analytics-' . now()->format('Y-m-d') . '.csv';
        return response()->stream(function () use ($rows) {
            $h = fopen('php://output', 'w');
            foreach ($rows as $row) fputcsv($h, $row);
            fclose($h);
        }, 200, ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""]);
    }
}

<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\TestResult;
use App\Models\Question;
use App\Models\Testimonial;
use App\Services\PlatformStatisticsService;

class DashboardController extends Controller
{
    public function index()
    {
        $registrationCounts = app(PlatformStatisticsService::class)->registrationCounts();
        $totalStudents = $registrationCounts['totalStudents'];
        $totalSchools = $registrationCounts['totalSchools'];
        $totalAdmins   = User::whereIn('role', ['admin', 'super_admin'])->count();
        $testsCompleted = TestResult::distinct('user_id')->count('user_id');

        $avgScore = TestResult::whereNotNull('total_score')->avg('total_score');
        $avgScore = $avgScore ? round($avgScore, 1) : null;

        $topCombo = TestResult::whereNotNull('recommended_combination')
            ->selectRaw('recommended_combination, COUNT(*) as cnt')
            ->groupBy('recommended_combination')
            ->orderByDesc('cnt')
            ->first();

        // Monthly test completion (last 6 months) — grouped in PHP to avoid MariaDB strict mode issues
        $trends = TestResult::select('created_at')
            ->where('created_at', '>=', now()->subMonths(6))
            ->get()
            ->groupBy(fn($r) => $r->created_at->format('Y-m'))
            ->sortKeys()
            ->map(fn($group, $key) => (object)[
                'month' => \Carbon\Carbon::createFromFormat('Y-m', $key)->format('M'),
                'count' => $group->count(),
            ])
            ->values();

        $recentActivity = collect(); // placeholder for logs

        $pendingTestimonials = Testimonial::where('approved', false)->with('user')->latest()->limit(5)->get();
        $pendingTestimonialsCount = Testimonial::where('approved', false)->count();

        return view('super-admin.dashboard.index', compact(
            'totalStudents', 'totalAdmins', 'totalSchools',
            'testsCompleted', 'avgScore', 'topCombo', 'trends',
            'pendingTestimonials', 'pendingTestimonialsCount'
        ));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $query = User::where('role', User::ROLE_STUDENT)
            ->with(['testResults' => fn($q) => $q->latest()->limit(1), 'student']);

        if (!$admin->isSuperAdmin() && $admin->school_name) {
            $query->whereRaw('LOWER(school_name) = ?', [strtolower($admin->school_name)]);
        }

        $students  = $query->get();
        $totalStudents  = $students->count();
        $testsCompleted = $students->filter(fn($u) => $u->testResults->isNotEmpty())->count();

        return view('admin.export', compact('students', 'totalStudents', 'testsCompleted', 'admin'));
    }

    public function download(Request $request)
    {
        $admin = Auth::user();
        $query = User::where('role', User::ROLE_STUDENT)
            ->with(['testResults' => fn($q) => $q->latest()->limit(1), 'student']);

        if (!$admin->isSuperAdmin() && $admin->school_name) {
            $query->whereRaw('LOWER(school_name) = ?', [strtolower($admin->school_name)]);
        }

        $students = $query->get();

        $rows[] = ['Name', 'Email', 'School', 'Status', 'Total Score (%)', 'Combination', 'Registered'];

        foreach ($students as $user) {
            $result = $user->testResults->first();
            $rows[] = [
                $user->name,
                $user->email,
                $user->school_name ?? optional($user->student)->school ?? '—',
                $result ? 'Completed' : 'Pending',
                $result ? round($result->total_score, 1) : '—',
                $result ? $result->recommended_combination : '—',
                $user->created_at->format('d M Y'),
            ];
        }

        $filename = 'pathfindr-results-' . now()->format('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TestResult;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->with(['testResults' => fn($q) => $q->latest()->limit(1), 'student']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%")
                ->orWhere('school_name', 'like', "%$s%"));
        }

        $students = $query->latest()->paginate(20)->withQueryString();
        return view('super-admin.students.index', compact('students'));
    }

    public function show(User $user)
    {
        abort_if(!$user->isStudent(), 404);
        $result  = $user->testResults()->latest()->first();
        $student = $user->student;
        return view('super-admin.students.show', compact('user', 'result', 'student'));
    }
}

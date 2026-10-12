<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        // Aggregate unique schools from students
        $query = User::where('role', 'student')
            ->whereNotNull('school_name')
            ->selectRaw('school_name, COUNT(*) as student_count, MIN(created_at) as first_seen')
            ->groupBy('school_name');

        if ($request->filled('search')) {
            $query->where('school_name', 'like', '%' . $request->search . '%');
        }

        $schools = $query->orderByDesc('student_count')->paginate(20)->withQueryString();
        return view('super-admin.schools.index', compact('schools'));
    }
}

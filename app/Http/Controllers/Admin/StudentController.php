<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StudentController extends Controller
{
    private function scopedStudents()
    {
        $admin = Auth::user();
        $query = User::where('role', User::ROLE_STUDENT)->with(['student', 'testResults']);

        if (!$admin->isSuperAdmin() && $admin->school_name) {
            $query->whereRaw('LOWER(school_name) = ?', [strtolower($admin->school_name)]);
        }

        return $query;
    }

    public function index()
    {
        $students = $this->scopedStudents()->latest()->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', Rules\Password::defaults()],
            'school_name'=> ['required', 'string', 'max:255'],
            'class'      => ['nullable', 'string', 'max:50'],
            'age'        => ['nullable', 'integer', 'min:8', 'max:20'],
            'gender'     => ['nullable', 'in:male,female,other'],
            'phone'      => ['nullable', 'string', 'max:20'],
        ]);

        // School admins can only add students to their own school
        $school = $admin->isSuperAdmin()
            ? $request->school_name
            : $admin->school_name;

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => User::ROLE_STUDENT,
            'school_name' => $school,
        ]);

        $user->student()->create([
            'school' => $school,
            'class'  => $request->class  ?? 'JSS3',
            'age'    => $request->age,
            'gender' => $request->gender,
            'phone'  => $request->phone,
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', "Student {$user->name} added successfully.");
    }

    public function destroy(User $user)
    {
        // Prevent deleting non-students or out-of-scope students
        $admin = Auth::user();
        abort_if(!$user->isStudent(), 403);

        if (!$admin->isSuperAdmin()) {
            abort_if(
                strtolower($user->school_name) !== strtolower($admin->school_name),
                403, 'You can only delete students from your school.'
            );
        }

        $user->delete();

        return back()->with('success', 'Student removed successfully.');
    }

    public function resetExam(User $user)
    {
        $admin = Auth::user();
        abort_if(!$user->isStudent(), 403);

        // School admin can only reset students from their own school
        if (!$admin->isSuperAdmin()) {
            abort_if(
                strtolower($user->school_name) !== strtolower($admin->school_name),
                403, 'You can only reset students from your school.'
            );
        }

        // Wipe test result, answers, and exam sessions
        $user->testResults()->delete();
        $user->answers()->delete();
        \App\Models\ExamSession::where('user_id', $user->id)->delete();

        // Clear any lingering session questions (can't clear another user's session,
        // but the DB records are gone so the exam will restart fresh)

        return back()->with('success', "{$user->name}'s exam has been reset. They can now retake it.");
    }
}

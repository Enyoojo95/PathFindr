<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['admin', 'super_admin']);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }
        $admins = $query->latest()->paginate(20)->withQueryString();
        return view('super-admin.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'school_name' => ['required', 'string'],
            'password'    => ['required', Rules\Password::defaults()],
        ]);

        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'admin',
            'school_name' => $request->school_name,
        ]);

        return back()->with('success', 'Admin created successfully.');
    }

    public function suspend(User $user)
    {
        abort_if(!$user->isAdmin(), 403, 'Only school admins can be suspended.');

        $user->is_suspended = !$user->is_suspended;
        $user->save();

        $status = $user->is_suspended ? 'suspended' : 'restored';

        return back()->with('success', "{$user->name} has been {$status}.");
    }

    public function destroy(User $user)
    {
        abort_if(!$user->isAdmin(), 403, 'Only school admins can be deleted here.');
        $user->delete();
        return back()->with('success', 'Admin deleted.');
    }
}

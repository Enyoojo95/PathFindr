<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('super_admin')->check()) {
            return redirect()->route('super-admin.dashboard');
        }
        return view('super-admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required'],
        ]);

        if (!Auth::guard('super_admin')->attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        $request->session()->regenerate();
        // Flag for welcome animation — one per session
        session(['sa_show_welcome' => true]);
        return redirect()->route('super-admin.dashboard');
    }

    public function showSignup()
    {
        abort_if(
            (string) config('services.super_admin.setup_code') === '' || SuperAdmin::query()->exists(),
            404
        );

        if (Auth::guard('super_admin')->check()) {
            return redirect()->route('super-admin.dashboard');
        }
        return view('super-admin.signup');
    }

    public function signup(Request $request)
    {
        $setupCode = (string) config('services.super_admin.setup_code');
        abort_if($setupCode === '' || SuperAdmin::query()->exists(), 404);

        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'unique:super_admins,email'],
            'secret_code' => ['required'],
            'password'    => ['required', 'confirmed', 'min:8'],
        ]);

        if (!hash_equals($setupCode, (string) $request->secret_code)) {
            return back()->withErrors(['secret_code' => 'Invalid master code.'])->withInput();
        }

        $admin = SuperAdmin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('super_admin')->login($admin);
        $request->session()->regenerate();
        session(['sa_show_welcome' => true]);

        return redirect()->route('super-admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('super_admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('super-admin.login');
    }

    public function showProfile()
    {
        return view('super-admin.profile', ['admin' => Auth::guard('super_admin')->user()]);
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('super_admin')->user();

        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'unique:super_admins,email,' . $admin->id],
            'phone'           => ['nullable', 'string', 'max:30'],
            'bio'             => ['nullable', 'string', 'max:500'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($admin->profile_picture) {
                Storage::disk('public')->delete($admin->profile_picture);
            }
            $admin->profile_picture = $request->file('profile_picture')->store('super-admin-avatars', 'public');
        }

        $admin->name  = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->bio   = $request->bio;
        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}

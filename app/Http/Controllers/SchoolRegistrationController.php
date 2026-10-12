<?php

namespace App\Http\Controllers;

use App\Models\SchoolRegistration;
use Illuminate\Http\Request;

class SchoolRegistrationController extends Controller
{
    public function create()
    {
        return view('school-registration');
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_name'        => ['required', 'string', 'max:255'],
            'address'            => ['required', 'string', 'max:500'],
            'country'            => ['required', 'string', 'max:100'],
            'state'              => ['required', 'string', 'max:100'],
            'email'              => ['required', 'email', 'unique:school_registrations,email'],
            'phone'              => ['required', 'string', 'max:30'],
            'website'            => ['nullable', 'url', 'max:255'],
            'number_of_classes'  => ['required', 'integer', 'min:1', 'max:500'],
            'student_count_range'=> ['required', 'string'],
            'reason'             => ['required', 'string', 'min:30', 'max:2000'],
        ]);

        SchoolRegistration::create($request->only([
            'school_name', 'address', 'country', 'state',
            'email', 'phone', 'website',
            'number_of_classes', 'student_count_range', 'reason',
        ]));

        return back()->with('submitted', true);
    }
}

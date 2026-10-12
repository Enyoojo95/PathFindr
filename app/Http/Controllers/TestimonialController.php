<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'body'   => ['required', 'string', 'min:10', 'max:500'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        // One testimonial per user — update if exists
        Testimonial::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'body'     => $request->body,
                'rating'   => $request->rating,
                'approved' => false, // reset approval on edit
            ]
        );

        return back()->with('testimonial_success', 'Thanks for sharing your experience!');
    }

    // Admin approve / delete
    public function approve(Testimonial $testimonial)
    {
        abort_if(!Auth::user()->hasAdminAccess(), 403);
        $testimonial->update(['approved' => true]);
        return back()->with('success', 'Testimonial approved.');
    }

    public function destroy(Testimonial $testimonial)
    {
        abort_if(!Auth::user()->hasAdminAccess(), 403);
        $testimonial->delete();
        return back()->with('success', 'Testimonial removed.');
    }
}

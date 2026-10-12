<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter'); // 'pending' | 'approved' | null

        $query = Testimonial::with('user')->latest();

        if ($filter === 'pending') {
            $query->where('approved', false);
        } elseif ($filter === 'approved') {
            $query->where('approved', true);
        }

        return view('super-admin.testimonials.index', [
            'testimonials' => $query->paginate(15)->withQueryString(),
            'filter'       => $filter,
            'pending'      => Testimonial::where('approved', false)->count(),
            'approved'     => Testimonial::where('approved', true)->count(),
            'total'        => Testimonial::count(),
        ]);
    }

    public function unapprove(Testimonial $testimonial)
    {
        $testimonial->update(['approved' => false]);
        return back()->with('success', 'Testimonial unpublished.');
    }
}

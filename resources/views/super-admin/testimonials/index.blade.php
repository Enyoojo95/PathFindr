@extends('layouts.super-admin')
@section('title', 'Testimonials — PathFindr Master')
@section('page-title', 'Testimonials')

@section('content')

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <h1 style="font-size:1.4rem;font-weight:900;color:#fff;margin-bottom:3px;">Student Testimonials</h1>
        <p style="font-size:0.78rem;color:rgba(255,255,255,0.45);">
            {{ $pending }} pending approval · {{ $approved }} published on homepage
        </p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ request()->fullUrlWithQuery(['filter'=>'pending']) }}"
           style="padding:8px 18px;border-radius:10px;font-size:0.78rem;font-weight:700;text-decoration:none;transition:all .2s;
                  {{ ($filter==='pending') ? 'background:rgba(245,158,11,0.25);border:1px solid rgba(245,158,11,0.5);color:#FDE68A;' : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.6);' }}">
            <i class="fas fa-clock" style="margin-right:5px;"></i> Pending ({{ $pending }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['filter'=>'approved']) }}"
           style="padding:8px 18px;border-radius:10px;font-size:0.78rem;font-weight:700;text-decoration:none;transition:all .2s;
                  {{ ($filter==='approved') ? 'background:rgba(16,185,129,0.25);border:1px solid rgba(16,185,129,0.5);color:#6EE7B7;' : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.6);' }}">
            <i class="fas fa-check-circle" style="margin-right:5px;"></i> Approved ({{ $approved }})
        </a>
        <a href="{{ route('super-admin.testimonials.index') }}"
           style="padding:8px 18px;border-radius:10px;font-size:0.78rem;font-weight:700;text-decoration:none;transition:all .2s;
                  {{ (!$filter) ? 'background:rgba(37,99,235,0.25);border:1px solid rgba(37,99,235,0.5);color:#93C5FD;' : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.6);' }}">
            <i class="fas fa-list" style="margin-right:5px;"></i> All ({{ $total }})
        </a>
    </div>
</div>

{{-- Flash messages --}}
@if(session('success'))
<div style="display:flex;align-items:center;gap:10px;padding:12px 18px;border-radius:12px;margin-bottom:16px;
            background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.35);color:#6EE7B7;font-size:0.83rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Empty state --}}
@if($testimonials->isEmpty())
<div class="glass-card" style="padding:60px 20px;text-align:center;">
    <div style="width:56px;height:56px;border-radius:50%;background:rgba(255,255,255,0.06);
                display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i class="fas fa-comment-dots" style="color:rgba(255,255,255,0.3);font-size:1.4rem;"></i>
    </div>
    <p style="color:rgba(255,255,255,0.5);font-size:0.9rem;">No testimonials found.</p>
</div>
@else

{{-- Testimonial cards --}}
<div style="display:flex;flex-direction:column;gap:12px;">
    @foreach($testimonials as $t)
    <div class="glass-card" style="padding:20px;transition:all .2s;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">

            {{-- Left: user info + body --}}
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <img src="{{ $t->user->avatarUrl() }}" alt="{{ $t->user->name }}"
                         style="width:38px;height:38px;border-radius:50%;object-fit:cover;
                                border:2px solid rgba(255,255,255,0.12);">
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:0.88rem;">{{ $t->user->name }}</div>
                        <div style="font-size:0.72rem;color:rgba(255,255,255,0.45);">
                            {{ $t->user->school_name ?? 'PathFindr Student' }}
                            &nbsp;·&nbsp; {{ $t->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    {{-- Status badge --}}
                    @if($t->approved)
                    <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;
                                 font-size:0.68rem;font-weight:700;
                                 background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.35);color:#6EE7B7;">
                        <i class="fas fa-check-circle" style="font-size:0.6rem;"></i> Published
                    </span>
                    @else
                    <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;
                                 font-size:0.68rem;font-weight:700;
                                 background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.35);color:#FDE68A;">
                        <i class="fas fa-clock" style="font-size:0.6rem;"></i> Pending
                    </span>
                    @endif
                </div>

                {{-- Star rating --}}
                <div style="display:flex;gap:3px;margin-bottom:8px;">
                    @for($s = 1; $s <= 5; $s++)
                    <i class="fa-star {{ $s <= $t->rating ? 'fas' : 'far' }}"
                       style="font-size:0.85rem;color:{{ $s <= $t->rating ? '#F59E0B' : 'rgba(255,255,255,0.15)' }};"></i>
                    @endfor
                    <span style="font-size:0.72rem;color:rgba(255,255,255,0.4);margin-left:6px;">{{ $t->rating }}/5</span>
                </div>

                {{-- Body --}}
                <p style="font-size:0.85rem;color:rgba(255,255,255,0.78);line-height:1.65;font-style:italic;">
                    "{{ $t->body }}"
                </p>
            </div>

            {{-- Right: actions --}}
            <div style="display:flex;flex-direction:column;gap:8px;min-width:130px;">
                @if(!$t->approved)
                <form method="POST" action="{{ route('testimonials.approve', $t) }}">
                    @csrf
                    <button type="submit"
                            style="width:100%;display:flex;align-items:center;justify-content:center;gap:7px;
                                   padding:9px 14px;border-radius:10px;border:none;cursor:pointer;font-size:0.78rem;font-weight:700;
                                   background:rgba(16,185,129,0.2);border:1px solid rgba(16,185,129,0.4);color:#6EE7B7;
                                   transition:all .18s;"
                            onmouseover="this.style.background='rgba(16,185,129,0.35)'"
                            onmouseout="this.style.background='rgba(16,185,129,0.2)'">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </form>
                @else
                <form method="POST" action="{{ route('super-admin.testimonials.unapprove', $t) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            style="width:100%;display:flex;align-items:center;justify-content:center;gap:7px;
                                   padding:9px 14px;border-radius:10px;border:none;cursor:pointer;font-size:0.78rem;font-weight:700;
                                   background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.35);color:#FDE68A;
                                   transition:all .18s;"
                            onmouseover="this.style.background='rgba(245,158,11,0.28)'"
                            onmouseout="this.style.background='rgba(245,158,11,0.15)'">
                        <i class="fas fa-eye-slash"></i> Unpublish
                    </button>
                </form>
                @endif

                <form method="POST" action="{{ route('testimonials.destroy', $t) }}"
                      onsubmit="return confirm('Delete this testimonial? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="width:100%;display:flex;align-items:center;justify-content:center;gap:7px;
                                   padding:9px 14px;border-radius:10px;border:none;cursor:pointer;font-size:0.78rem;font-weight:700;
                                   background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:rgba(252,165,165,0.9);
                                   transition:all .18s;"
                            onmouseover="this.style.background='rgba(239,68,68,0.25)'"
                            onmouseout="this.style.background='rgba(239,68,68,0.12)'">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($testimonials->hasPages())
<div style="margin-top:16px;display:flex;justify-content:center;">
    {{ $testimonials->links() }}
</div>
@endif

@endif
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PathFindr Report — {{ $user->name }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
@page { margin:0; size: A4 portrait; }
html  { margin:0; padding:0; }
body  {
    margin: 0;
    padding: 0;
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 10px;
    color: #1e293b;
    background: #fff;
    /* Reserve space at the bottom equal to the fixed footer height */
    padding-bottom: 44px;
}

/* ══════════════════════════════════
   FIXED FOOTER — DomPDF renders
   position:fixed at the very bottom
   of every page it appears on.
══════════════════════════════════ */
.footer {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    height: 44px;
    background: #0f1f5c;
    padding: 0 28px;
    display: block;
    width: 100%;
}
.ftr-inner { display: table; width: 100%; height: 44px; }
.ftr-l     { display: table-cell; vertical-align: middle; }
.ftr-r     { display: table-cell; vertical-align: middle; text-align: right; }
.ftr-main  { font-size: 8.5px; font-weight: 700; color: rgba(255,255,255,0.85); }
.ftr-sub   { font-size: 7px; color: rgba(255,255,255,0.4); margin-top: 2px; }
.ftr-tag   { font-size: 7.5px; color: rgba(255,255,255,0.45); }

/* ══════════════════════════════════
   HEADER
══════════════════════════════════ */
.header {
    background: #0f1f5c;
    padding: 18px 28px 15px;
    width: 100%;
}
.hdr-tbl { display: table; width: 100%; }
.hdr-l   { display: table-cell; vertical-align: middle; }
.hdr-r   { display: table-cell; vertical-align: middle; text-align: right; width: 200px; }

.brand    { font-size: 23px; font-weight: 900; color: #fff; letter-spacing: -0.5px; line-height: 1; }
.brand em { color: #F5A623; font-style: normal; }
.tagline  { font-size: 6.5px; color: rgba(255,255,255,0.38); letter-spacing: 0.22em; text-transform: uppercase; margin-top: 3px; }

.rpt-t  { font-size: 9px; font-weight: 700; color: rgba(255,255,255,0.82); }
.rpt-d  { font-size: 8px; color: rgba(255,255,255,0.48); margin-top: 3px; }

/* Gold accent line */
.accent { height: 3px; background: #F5A623; }

/* ══════════════════════════════════
   HERO STRIP
══════════════════════════════════ */
.hero    { background: #1a2f7a; display: table; width: 100%; }
.hero-l  { display: table-cell; vertical-align: middle; padding: 13px 28px; }
.hero-r  { display: table-cell; vertical-align: middle; text-align: right; padding: 13px 28px; width: 155px; }

.hero-name { font-size: 17px; font-weight: 900; color: #fff; line-height: 1; }
.hero-meta { font-size: 7.5px; color: rgba(255,255,255,0.52); margin-top: 3px; }

.badge     { display: inline-block; background: #F5A623; border-radius: 10px; padding: 8px 18px; text-align: center; }
.badge-num { font-size: 22px; font-weight: 900; color: #0f1f5c; line-height: 1; }
.badge-u   { font-size: 12px; font-weight: 700; color: rgba(15,31,92,0.55); }
.badge-lbl { font-size: 6.5px; font-weight: 700; color: rgba(15,31,92,0.55); text-transform: uppercase; letter-spacing: 0.12em; margin-top: 1px; }

/* ══════════════════════════════════
   BODY
══════════════════════════════════ */
.body { padding: 18px 28px 0; }

.sec-head {
    font-size: 7.5px; font-weight: 700; color: #1A2A6C;
    text-transform: uppercase; letter-spacing: 0.16em;
    padding-left: 8px; border-left: 3px solid #F5A623;
    margin-bottom: 8px; line-height: 1;
}
.spacer-v { height: 16px; }

/* Info grid */
.info-grid { display: table; width: 100%; border-collapse: collapse; }
.ig-cell   { display: table-cell; width: 25%; background: #f4f8ff; border: 1px solid #dce8fb; padding: 8px 11px; vertical-align: top; }
.ig-lbl    { font-size: 7px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; }
.ig-val    { font-size: 10px; font-weight: 700; color: #1A2A6C; margin-top: 3px; }

/* Combo banner */
.combo      { background: #0f1f5c; border-radius: 9px; padding: 14px 22px; display: table; width: 100%; }
.combo-l    { display: table-cell; vertical-align: middle; }
.combo-r    { display: table-cell; vertical-align: middle; text-align: right; width: 80px; }
.combo-tag  { font-size: 7px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.14em; margin-bottom: 3px; }
.combo-name { font-size: 26px; font-weight: 900; color: #fff; line-height: 1; }
.combo-subs { font-size: 8.5px; color: rgba(255,255,255,0.55); margin-top: 5px; }
.combo-icon { width: 52px; height: 52px; border-radius: 13px; border: 2px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.08); font-size: 24px; font-weight: 900; line-height: 48px; text-align: center; display: inline-block; vertical-align: middle; }

.not-det { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 10px 14px; text-align: center; color: #92400e; font-size: 9.5px; }

/* Score analysis */
.analysis { display: table; width: 100%; border-collapse: collapse; }
.an-l     { display: table-cell; width: 45%; vertical-align: top; padding-right: 10px; }
.an-r     { display: table-cell; width: 55%; vertical-align: top; padding-left: 10px; }
.an-box   { background: #f4f8ff; border: 1px solid #dce8fb; border-radius: 9px; padding: 12px; }
.an-sub   { font-size: 7px; font-weight: 700; color: #1A2A6C; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 10px; }

.br       { margin-bottom: 9px; }
.br-top   { display: table; width: 100%; margin-bottom: 3px; }
.br-name  { display: table-cell; font-size: 9.5px; font-weight: 600; color: #374151; }
.br-pct   { display: table-cell; text-align: right; font-size: 9.5px; font-weight: 800; }
.br-track { height: 9px; background: #e2eaf8; border-radius: 99px; overflow: hidden; }
.br-fill  { height: 100%; border-radius: 99px; }

.total-box { background: #1A2A6C; border-radius: 8px; padding: 10px; text-align: center; margin-top: 12px; }
.total-num { font-size: 22px; font-weight: 900; color: #F5A623; line-height: 1; }
.total-lbl { font-size: 7px; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 0.12em; margin-top: 2px; }

/* Careers / Universities */
.two-col  { display: table; width: 100%; }
.tc-a     { display: table-cell; width: 55%; vertical-align: top; padding-right: 12px; }
.tc-b     { display: table-cell; width: 45%; vertical-align: top; padding-left: 12px; border-left: 1px solid #e2eaf8; }
.tc-sub   { font-size: 7px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 7px; }

.li      { display: table; width: 100%; background: #f4f8ff; border: 1px solid #dce8fb; border-radius: 6px; padding: 6px 10px; margin-bottom: 5px; }
.li-num  { display: table-cell; width: 18px; height: 18px; background: #1A2A6C; border-radius: 50%; font-size: 8px; font-weight: 900; color: #fff; text-align: center; line-height: 18px; vertical-align: middle; }
.li-txt  { display: table-cell; padding-left: 8px; font-size: 9.5px; color: #1e293b; font-weight: 500; vertical-align: middle; }

.motiv   { background: #f0fff4; border-left: 4px solid #43A047; padding: 10px 14px; border-radius: 0 8px 8px 0; font-size: 9.5px; line-height: 1.7; color: #14532d; font-style: italic; }
.empty-t { color: #94a3b8; font-size: 9px; padding: 5px 0; font-style: italic; }
</style>
</head>
<body>

{{-- ══ FIXED FOOTER — renders at bottom of every page ══ --}}
<div class="footer">
    <div class="ftr-inner">
        <div class="ftr-l">
            <div class="ftr-main">Issued by Crystalfield Academy, Karshi, Abuja</div>
            <div class="ftr-sub">Powered by PathFindr &nbsp;·&nbsp; Confidential Student Report</div>
        </div>
        <div class="ftr-r">
            <div class="ftr-tag">Know Your Strengths. Choose Your Future.</div>
        </div>
    </div>
</div>

{{-- ══ HEADER ══ --}}
<div class="header">
    <div class="hdr-tbl">
        <div class="hdr-l">
            <div class="brand">Path<em>Findr</em></div>
            <div class="tagline">Know Your Strengths. Choose Your Future.</div>
        </div>
        <div class="hdr-r">
            <div class="rpt-t">Psychometric Assessment Report</div>
            <div class="rpt-d">{{ now()->format('d F Y') }}</div>
        </div>
    </div>
</div>
<div class="accent"></div>

{{-- ══ HERO ══ --}}
<div class="hero">
    <div class="hero-l">
        <div class="hero-name">{{ $user->name }}</div>
        <div class="hero-meta">
            {{ $user->school_name ?? ($student->school ?? 'PathFindr Student') }}
            &nbsp;·&nbsp; {{ $student->class ?? 'JSS3' }}
            &nbsp;·&nbsp; {{ $result->created_at->format('d M Y') }}
        </div>
    </div>
    <div class="hero-r">
        <div class="badge">
            <div class="badge-num">{{ round($result->total_score, 1) }}<span class="badge-u">%</span></div>
            <div class="badge-lbl">Overall Score</div>
        </div>
    </div>
</div>

{{-- ══ BODY ══ --}}
<div class="body">

    {{-- Student Information --}}
    <div class="sec-head">Student Information</div>
    <table class="info-grid">
        <tr>
            <td class="ig-cell">
                <div class="ig-lbl">Full Name</div>
                <div class="ig-val">{{ $user->name }}</div>
            </td>
            <td class="ig-cell">
                <div class="ig-lbl">Email Address</div>
                <div class="ig-val" style="font-size:8px;">{{ $user->email }}</div>
            </td>
            <td class="ig-cell">
                <div class="ig-lbl">School</div>
                <div class="ig-val" style="font-size:9px;">{{ $user->school_name ?? ($student->school ?? '—') }}</div>
            </td>
            <td class="ig-cell">
                <div class="ig-lbl">Class &amp; Date</div>
                <div class="ig-val">{{ $student->class ?? 'JSS3' }} · {{ $result->created_at->format('d M Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="spacer-v"></div>

    {{-- Recommended Combination --}}
    @if($result->recommended_combination)
    @php
        $comboIcon  = match($result->recommended_combination) { 'Science'=>'S','Commercial'=>'C','Arts'=>'A',default=>'?' };
        $comboSubs  = match($result->recommended_combination) {
            'Science'    => 'Physics · Chemistry · Biology · Mathematics',
            'Commercial' => 'Economics · Accounting · Commerce · Business Studies',
            'Arts'       => 'Literature · Government · History · CRK / IRS',
            default      => '',
        };
        $comboColor = match($result->recommended_combination) { 'Science'=>'#43A047','Commercial'=>'#F59E0B','Arts'=>'#a78bfa',default=>'#F5A623' };
    @endphp
    <div class="sec-head">Recommended Subject Combination</div>
    <div class="combo">
        <div class="combo-l">
            <div class="combo-tag">Best Match For You</div>
            <div class="combo-name">{{ $result->recommended_combination }}</div>
            <div class="combo-subs">{{ $comboSubs }}</div>
        </div>
        <div class="combo-r">
            <div class="combo-icon" style="color:{{ $comboColor }};">{{ $comboIcon }}</div>
        </div>
    </div>
    @else
    <div class="sec-head">Recommended Subject Combination</div>
    <div class="not-det">
        <strong>Recommendation Not Available</strong><br>
        Fewer than 10 questions were answered. Please retake the assessment.
    </div>
    @endif

    <div class="spacer-v"></div>

    {{-- Score Analysis --}}
    <div class="sec-head">Score Analysis</div>
    <div class="analysis">
        <div class="an-l">
            <div class="an-box">
                <div class="an-sub">Performance by Category</div>
                {!! $chartSvg !!}
            </div>
        </div>
        <div class="an-r">
            <div class="an-box">
                <div class="an-sub">Score Breakdown</div>
                @php
                $bars = [
                    ['label'=>'Numerical','score'=>$result->numerical_score,'color'=>'#1A2A6C'],
                    ['label'=>'Verbal',   'score'=>$result->verbal_score,   'color'=>'#2D5F9E'],
                    ['label'=>'Spatial',  'score'=>$result->spatial_score,  'color'=>'#7C3AED'],
                    ['label'=>'Interest', 'score'=>$result->interest_score, 'color'=>'#43A047'],
                ];
                @endphp
                @foreach($bars as $b)
                @php $s = round($b['score'] ?? 0, 1); @endphp
                <div class="br">
                    <div class="br-top">
                        <span class="br-name">{{ $b['label'] }}</span>
                        <span class="br-pct" style="color:{{ $b['color'] }};">{{ $s }}%</span>
                    </div>
                    <div class="br-track">
                        <div class="br-fill" style="width:{{ $s }}%;background:{{ $b['color'] }};"></div>
                    </div>
                </div>
                @endforeach
                <div class="total-box">
                    <div class="total-num">{{ round($result->total_score,1) }}<span style="font-size:12px;color:rgba(255,255,255,0.4);">%</span></div>
                    <div class="total-lbl">Overall Score</div>
                </div>
            </div>
        </div>
    </div>

    <div class="spacer-v"></div>

    {{-- Career & University Matches --}}
    <div class="sec-head">Career &amp; University Matches</div>
    <div class="two-col">
        <div class="tc-a">
            <div class="tc-sub">Career Paths</div>
            @forelse(array_slice($careers, 0, 5) as $i => $career)
            <div class="li">
                <span class="li-num">{{ $i+1 }}</span>
                <span class="li-txt">{{ is_array($career) ? ($career['title'] ?? $career['name'] ?? json_encode($career)) : $career }}</span>
            </div>
            @empty
            <div class="empty-t">No career data available.</div>
            @endforelse
        </div>
        <div class="tc-b">
            <div class="tc-sub">University Suggestions</div>
            @forelse(array_slice($universities, 0, 4) as $i => $uni)
            <div class="li">
                <span class="li-num">{{ $i+1 }}</span>
                <span class="li-txt">{{ is_array($uni) ? ($uni['name'] ?? json_encode($uni)) : $uni }}</span>
            </div>
            @empty
            <div class="empty-t">No university data available.</div>
            @endforelse
        </div>
    </div>

    {{-- Motivational Message --}}
    @if(!empty($result->motivational_message))
    <div class="spacer-v"></div>
    <div class="sec-head">Personal Message</div>
    <div class="motiv">"{{ $result->motivational_message }}"</div>
    @endif

</div>{{-- .body --}}

</body>
</html>

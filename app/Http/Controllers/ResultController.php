<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultController extends Controller
{
    public function downloadPdf(int $id)
    {
        $result = TestResult::with('user.student')->findOrFail($id);

        $admin = Auth::user();

        // Student can download their own. Admin/super-admin can download
        // results for students registered under their school.
        if ($result->user_id !== $admin->id) {
            abort_if(!$admin->hasAdminAccess(), 403);

            if (!$admin->isSuperAdmin()) {
                abort_if(
                    strtolower($admin->school_name ?? '') !== strtolower($result->user->school_name ?? ''),
                    403,
                    'You can only download reports for students at your school.'
                );
            }
        }

        $user    = $result->user;
        $student = $user->student;

        // ── Build bar chart as inline SVG ────────────────────────
        $scores = [
            'Numerical' => (float) ($result->numerical_score ?? 0),
            'Verbal'    => (float) ($result->verbal_score    ?? 0),
            'Spatial'   => (float) ($result->spatial_score   ?? 0),
            'Interest'  => (float) ($result->interest_score  ?? 0),
        ];

        $chartSvg = $this->buildBarChart($scores);

        // ── Decode JSON columns ───────────────────────────────────
        $careers      = is_array($result->careers)      ? $result->careers      : [];
        $universities = is_array($result->universities) ? $result->universities : [];

        $pdf = Pdf::loadView('pdf.report', compact(
            'result', 'user', 'student', 'scores', 'chartSvg', 'careers', 'universities'
        ))->setPaper('a4', 'portrait')
          ->setOption('margin_top', 0)
          ->setOption('margin_bottom', 0)
          ->setOption('margin_left', 0)
          ->setOption('margin_right', 0);

        $filename = 'PathFindr-Report-' . str_replace(' ', '-', $user->name) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Build a vertical bar chart using pure HTML/CSS tables.
     * DomPDF renders block elements and tables reliably — SVG gradients/paths do not.
     * Max bar height = 100px (1px per %).
     */
    private function buildBarChart(array $scores): string
    {
        $colors = [
            'Numerical' => '#1A2A6C',
            'Verbal'    => '#2D5F9E',
            'Spatial'   => '#7C3AED',
            'Interest'  => '#43A047',
        ];

        $maxBarPx = 100; // height in px that represents 100%

        $html  = '<table style="width:100%;border-collapse:collapse;" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';

        foreach ($scores as $label => $val) {
            $val   = min(100, max(0, (float) $val));
            $pct   = round($val, 1);
            $color = $colors[$label] ?? '#1A2A6C';
            $barPx = max(3, round($val / 100 * $maxBarPx));

            $html .= '<td style="vertical-align:bottom;text-align:center;padding:0 6px;height:' . ($maxBarPx + 28) . 'px;">';

            // % label above bar
            $html .= '<div style="font-size:8.5px;font-weight:800;color:' . $color . ';margin-bottom:4px;">' . $pct . '%</div>';

            // bar
            $html .= '<div style="height:' . $barPx . 'px;background:' . $color . ';border-radius:4px 4px 0 0;width:100%;"></div>';

            // thin baseline
            $html .= '<div style="height:2px;background:#cbd5e1;width:100%;margin-bottom:5px;"></div>';

            // category label
            $html .= '<div style="font-size:8.5px;font-weight:700;color:#374151;">' . $label . '</div>';

            $html .= '</td>';
        }

        $html .= '</tr></table>';

        return $html;
    }
}

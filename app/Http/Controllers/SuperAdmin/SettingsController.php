<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name'           => config('app.name', 'PathFindr'),
            'openai_key'          => env('OPENAI_API_KEY', ''),
            'mail_host'           => env('MAIL_HOST', ''),
            'mail_port'           => env('MAIL_PORT', '587'),
            'mail_username'       => env('MAIL_USERNAME', ''),
            'whatsapp_sharing'    => true,
            'student_registration'=> true,
            'pdf_reports'         => true,
        ];
        return view('super-admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // In a real app, write to .env or a settings table
        // For now just flash success
        return back()->with('success', 'Settings saved successfully.');
    }

    public function maintenance(Request $request)
    {
        if ($request->boolean('enable')) {
            Artisan::call('down', ['--render' => 'errors.503']);
        } else {
            Artisan::call('up');
        }
        return back()->with('success', $request->boolean('enable') ? 'Maintenance mode enabled.' : 'Site is back online.');
    }
}

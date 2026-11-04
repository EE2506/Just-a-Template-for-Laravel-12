<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * WM-04: DJ Punzalan · https://github.com/EE2506 · {{AUTHOR_EMAIL}}
 */
class SessionController extends Controller
{
    public function revokeCurrent(Request $request): RedirectResponse
    {
        DB::table(config('session.table', 'sessions'))
            ->where('id', $request->session()->getId())
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Inertia::clearHistory();

        return redirect()->route('login');
    }

    public function revokeAll(Request $request): RedirectResponse
    {
        DB::table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Inertia::clearHistory();

        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class AdminForgotPasswordController extends Controller
{
    public function showLinkRequestForm(): View
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // Mindig elküldjük a kérést, de a választ nem differenciáljuk,
        // hogy ne lehessen kideríteni, melyik e-mail cím regisztrált.
        try {
            Password::broker('admins')->sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            Log::error('Admin jelszó-visszaállítás: az e-mail küldése sikertelen.', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['email' => 'Az e-mail küldése most nem sikerült. Kérjük, próbáld újra később.']);
        }

        return back()->with('status', 'Ha ez az e-mail cím regisztrált, hamarosan megérkezik a visszaállítási link.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Illuminate\View\View;

class AdminResetPasswordController extends Controller
{
    private const STATUS_MESSAGES = [
        Password::INVALID_TOKEN   => 'A visszaállítási link érvénytelen vagy lejárt. Kérj újat.',
        Password::INVALID_USER    => 'Ehhez az e-mail címhez nem tartozik admin fiók.',
        Password::RESET_THROTTLED => 'Túl sok próbálkozás. Kérjük, várj egy kicsit, és próbáld újra.',
    ];

    public function showResetForm(Request $request, string $token): View
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRules::min(10)->mixedCase()->numbers()->symbols()],
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, string $password) {
                $admin->forceFill(['password' => Hash::make($password)])
                    ->setRememberToken(Str::random(60));

                $admin->save();

                event(new PasswordReset($admin));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('status', 'Jelszó sikeresen megváltoztatva. Kérjük, jelentkezz be.')
            : back()->withErrors(['email' => self::STATUS_MESSAGES[$status] ?? 'A jelszó visszaállítása nem sikerült.']);
    }
}

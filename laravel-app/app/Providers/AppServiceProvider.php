<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        RedirectIfAuthenticated::redirectUsing(function () {
            if (Auth::guard('admin')->check()) {
                return route('admin.dashboard');
            }

            return '/';
        });

        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('admin.password.email', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('admin.password.reset', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url    = route('admin.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
            $expire = config('auth.passwords.admins.expire');

            return (new MailMessage)
                ->subject('Jelszó visszaállítása – ' . config('app.name'))
                ->greeting('Szia!')
                ->line('Azért kaptad ezt a levelet, mert jelszó-visszaállítást kértek az admin fiókodhoz.')
                ->action('Új jelszó beállítása', $url)
                ->line("A link {$expire} percig érvényes.")
                ->line('Ha nem te kérted, nincs teendőd, a jelszavad nem változik.')
                ->salutation('Üdvözlettel: ' . config('app.name'));
        });
    }
}

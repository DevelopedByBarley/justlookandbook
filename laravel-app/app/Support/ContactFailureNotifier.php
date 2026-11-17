<?php

namespace App\Support;

use App\Mail\ContactFailedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Értesítést küld, ha egy látogató nem tudta elküldeni a kapcsolati űrlapot,
 * a beküldött adatokkal együtt, hogy kézzel fel lehessen venni vele a kapcsolatot.
 *
 * Soha nem dob kivételt: az értesítés hibája nem ronthatja el a látogató élményét.
 */
class ContactFailureNotifier
{
    public const SEND_FAILED = 'send_failed';
    public const EXPIRED     = 'expired';
    public const TOO_MANY    = 'too_many';
    public const ERROR       = 'error';

    private const REASONS = [
        self::SEND_FAILED => 'Az e-mail küldése nem sikerült (SMTP-hiba vagy időtúllépés).',
        self::EXPIRED     => 'Lejárt a munkamenet (419): az oldal túl sokáig volt nyitva.',
        self::TOO_MANY    => 'Túl sok próbálkozás (429): a látogatót a rate limit blokkolta.',
        self::ERROR       => 'Váratlan hiba történt az űrlap feldolgozásakor (500).',
    ];

    // Először a szerver saját sendmail-je, így akkor is kimegy az értesítés, ha épp az SMTP a hibás
    private const MAILERS = ['sendmail', 'smtp'];

    // Óránként legfeljebb ennyi értesítés, hogy egy bot-roham ne árassza el a postafiókot
    private const MAX_PER_HOUR = 20;

    public static function notify(string $reason, Request $request, ?\Throwable $error = null): void
    {
        try {
            // Kitöltött honeypot = bot, nem kell értesíteni
            if ($request->filled('contact_hp')) {
                return;
            }

            // 419/429 esetén IP-nként 10 percenként egy értesítés elég
            if (in_array($reason, [self::EXPIRED, self::TOO_MANY], true)
                && ! Cache::add("contact-alert:{$reason}:{$request->ip()}", true, 600)) {
                return;
            }

            if (! RateLimiter::attempt('contact-alert', self::MAX_PER_HOUR, fn () => true, 3600)) {
                return;
            }

            $data = $request->only('name', 'email', 'subject', 'message');

            $meta = [
                'time'       => now()->format('Y-m-d H:i:s'),
                'page'       => $request->headers->get('referer'),
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
                'locale'     => app()->getLocale(),
                'error'      => $error?->getMessage(),
            ];

            $mail = new ContactFailedMail(self::REASONS[$reason] ?? $reason, $data, $meta);
        } catch (\Throwable $e) {
            self::logFailure($reason, $request, $e);

            return;
        }

        // Egyenként próbáljuk a küldési módokat: ha az egyik hibás (akár már a felépítéskor),
        // a következő még kiviheti az értesítést
        foreach (self::MAILERS as $mailer) {
            try {
                Mail::mailer($mailer)->to(config('mail.from.address'))->send($mail);

                return;
            } catch (\Throwable $e) {
                $lastError = $e;
            }
        }

        self::logFailure($reason, $request, $lastError);
    }

    private static function logFailure(string $reason, Request $request, \Throwable $e): void
    {
        Log::error('Kapcsolati űrlap: a hibaértesítést sem sikerült elküldeni.', [
            'reason' => $reason,
            'error'  => $e->getMessage(),
            'data'   => $request->only('name', 'email', 'subject', 'message'),
        ]);
    }
}

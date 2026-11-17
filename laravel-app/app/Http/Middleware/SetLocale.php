<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED = ['hu', 'en', 'it'];

    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale(self::resolve());

        return $next($request);
    }

    public static function resolve(): string
    {
        $locale = session('locale', config('app.locale'));

        if (! in_array($locale, self::SUPPORTED)) {
            $locale = config('app.locale');
        }

        return $locale;
    }
}

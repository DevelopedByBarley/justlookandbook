<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SetLocale;
use App\Support\ContactFailureNotifier;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn (\Illuminate\Http\Request $request) =>
            $request->is('admin/*') ? route('admin.login') : route('login')
        );

        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\SetLocale::class,
            //\App\Http\Middleware\CheckLicense::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Kapcsolati űrlap: lejárt CSRF token (419) vagy throttle (429) esetén ne hibaoldal
        // jelenjen meg, hanem vissza az űrlapra a beírt adatokkal és egy érthető üzenettel.
        $exceptions->render(function (HttpExceptionInterface $e, \Illuminate\Http\Request $request) {
            if (! $request->routeIs('contact.store') || ! in_array($e->getStatusCode(), [419, 429])) {
                return null;
            }

            // A CSRF és a throttle a SetLocale előtt fut, ezért itt kell beállítani a nyelvet
            app()->setLocale(SetLocale::resolve());

            $expired = $e->getStatusCode() === 419;

            ContactFailureNotifier::notify(
                $expired ? ContactFailureNotifier::EXPIRED : ContactFailureNotifier::TOO_MANY,
                $request,
            );

            return back()
                ->withInput($request->except('_token', 'contact_hp'))
                ->withErrors(['contact' => __($expired ? 'contact.expired' : 'contact.too_many')])
                ->withFragment('contact');
        });

        // Váratlan hiba (500) a kapcsolati űrlapnál: a látogató üzenete elveszne, ezért értesítünk
        $exceptions->report(function (\Throwable $e) {
            $request = request();

            if ($request->routeIs('contact.store')) {
                ContactFailureNotifier::notify(ContactFailureNotifier::ERROR, $request, $e);
            }
        });
    })->create();

// The web root lives next to the app folder (see ../justlookandbook.com/index.php),
// so console commands like "artisan serve" need to know about it too.
if (is_dir($publicPath = dirname(__DIR__, 2).'/justlookandbook.com')) {
    $app->usePublicPath($publicPath);
}

return $app;
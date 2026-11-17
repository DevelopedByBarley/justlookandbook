<?php

use App\Models\Program;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $topicSlug = $request->query('topic');

    $query = Program::with(['topics', 'coverImage'])
        ->where('active', true)
        ->orderBy('sort_order')
        ->orderBy('id');

    if ($topicSlug) {
        $query->whereHas('topics', fn ($q) => $q->where('slug', $topicSlug));
    }

    $programs    = $query->paginate(20)->withQueryString();
    $topics      = Topic::orderBy('id')->get();
    $activeTopic = $topicSlug;

    $popularIds = Program::where('active', true)
        ->orderByDesc('views')
        ->limit(3)
        ->pluck('id');

    return view('welcome', compact('programs', 'topics', 'activeTopic', 'popularIds'));
})->name('home');


Route::get('/msg-test', function () {
    return response(file_get_contents(public_path('msg-test.html')))
        ->header('Content-Type', 'text/html');
});

Route::get('/sitemap.xml', function () {
    $programs = Program::where('active', true)->get(['slug', 'updated_at']);

    return response()->view('sitemap', compact('programs'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['hu', 'en', 'it'])) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('lang.switch');

require __DIR__.'/programs.php';
require __DIR__.'/contact.php';
require __DIR__.'/auth/admin.php';
require __DIR__.'/auth/admin-password.php';

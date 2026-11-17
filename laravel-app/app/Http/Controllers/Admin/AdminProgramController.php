<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramImage;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProgramController extends Controller
{
    public function index(): View
    {
        
        $programs = Program::with('topics')->orderBy('sort_order')->orderBy('id')->paginate(20);

        return view('admin.programs.index', compact('programs'));
    }

    public function order(): View
    {
        $programs = Program::orderBy('sort_order')->orderBy('id')->get(['id', 'name', 'slug', 'active']);

        return view('admin.programs.order', compact('programs'));
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer', 'distinct'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['ids'] as $i => $id) {
                // toBase(): ne írja át az updated_at-et (a sitemap lastmod-ja abból jön)
                Program::whereKey($id)->toBase()->update(['sort_order' => $i + 1]);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function create(): View
    {
        $topics = Topic::orderBy('name->hu')->get();

        return view('admin.programs.create', compact('topics'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name.hu'        => ['required', 'string', 'max:200'],
            'name.en'        => ['required', 'string', 'max:200'],
            'name.it'        => ['nullable', 'string', 'max:200'],
            'description.hu' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'description.it' => ['nullable', 'string'],
            'topic_ids'      => ['nullable', 'array'],
            'topic_ids.*'    => ['integer', 'exists:topics,id'],
            'active'         => ['required', 'boolean'],
            'price_adult'    => ['nullable', 'integer', 'min:0'],
            'price_child'    => ['nullable', 'integer', 'min:0'],
            'child_age_min'  => ['nullable', 'integer', 'min:0', 'max:18', 'lte:child_age_max'],
            'child_age_max'  => ['nullable', 'integer', 'min:0', 'max:18', 'gte:child_age_min'],
            'duration.hu'    => ['nullable', 'string', 'max:100'],
            'duration.en'    => ['nullable', 'string', 'max:100'],
            'duration.it'    => ['nullable', 'string', 'max:100'],
            'available_days' => ['nullable', 'array'],
            'available_days.*' => ['string', 'in:mon,tue,wed,thu,fri,sat,sun'],
            'youtube_embed'  => ['nullable', 'string', 'max:255'],
            'images'         => ['nullable', 'array', 'max:7'],
            'images.*'       => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:3072'],
        ], [
            'name.hu.required'       => 'A magyar név megadása kötelező.',
            'name.en.required'       => 'Az angol név megadása kötelező.',
            'topic_ids.*.exists'     => 'Az egyik kiválasztott téma nem létezik.',
            'images.max'             => 'Legfeljebb 7 képet tölthetsz fel.',
            'images.*.image'         => 'Csak képfájl tölthető fel.',
            'images.*.max'           => 'Egy kép mérete legfeljebb 3 MB lehet.',
            'child_age_min.lte'      => 'A minimum életkor nem lehet nagyobb a maximumnál.',
            'child_age_max.gte'      => 'A maximum életkor nem lehet kisebb a minimumnál.',
        ]);

        $data = $validated;
        $data['slug'] = $this->uniqueSlug($validated['name']['en']);
        $data['available_days'] = $validated['available_days'] ?? [];
        $data['youtube_embed'] = $validated['youtube_embed'] ?? null;
        $data['sort_order'] = (Program::max('sort_order') ?? 0) + 1;
        unset($data['topic_ids']);

        $program = Program::create($data);

        $program->topics()->sync($validated['topic_ids'] ?? []);

        $this->saveImages($request, $program);

        return redirect()->route('admin.programs.edit', $program)
            ->with('success', 'Program sikeresen létrehozva.');
    }

    public function edit(Program $program): View
    {
        $topics = Topic::orderBy('name->hu')->get();
        $program->load('images');

        return view('admin.programs.edit', compact('program', 'topics'));
    }

    public function update(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'name.hu'        => ['required', 'string', 'max:200'],
            'name.en'        => ['required', 'string', 'max:200'],
            'name.it'        => ['nullable', 'string', 'max:200'],
            'description.hu' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'description.it' => ['nullable', 'string'],
            'topic_ids'      => ['nullable', 'array'],
            'topic_ids.*'    => ['integer', 'exists:topics,id'],
            'active'         => ['required', 'boolean'],
            'price_adult'    => ['nullable', 'integer', 'min:0'],
            'price_child'    => ['nullable', 'integer', 'min:0'],
            'child_age_min'  => ['nullable', 'integer', 'min:0', 'max:18', 'lte:child_age_max'],
            'child_age_max'  => ['nullable', 'integer', 'min:0', 'max:18', 'gte:child_age_min'],
            'duration.hu'    => ['nullable', 'string', 'max:100'],
            'duration.en'    => ['nullable', 'string', 'max:100'],
            'duration.it'    => ['nullable', 'string', 'max:100'],
            'available_days' => ['nullable', 'array'],
            'available_days.*' => ['string', 'in:mon,tue,wed,thu,fri,sat,sun'],
            'youtube_embed'  => ['nullable', 'string', 'max:255'],
        ], [
            'name.hu.required'   => 'A magyar név megadása kötelező.',
            'name.en.required'   => 'Az angol név megadása kötelező.',
            'topic_ids.*.exists' => 'Az egyik kiválasztott téma nem létezik.',
            'child_age_min.lte'  => 'A minimum életkor nem lehet nagyobb a maximumnál.',
            'child_age_max.gte'  => 'A maximum életkor nem lehet kisebb a minimumnál.',
        ]);

        $data = $validated;
        $data['slug'] = $this->uniqueSlug($validated['name']['en'], $program->id);
        $data['available_days'] = $validated['available_days'] ?? [];
        $data['youtube_embed'] = $validated['youtube_embed'] ?? null;
        unset($data['topic_ids']);

        $program->update($data);
        $program->topics()->sync($validated['topic_ids'] ?? []);

        // Törlésre jelölt képek
        if ($deleteIds = $request->input('delete_images', [])) {
            ProgramImage::whereIn('id', $deleteIds)
                ->where('program_id', $program->id)
                ->each(fn ($img) => $img->delete());
        }

        // Borítókép beállítása
        if ($coverId = $request->input('cover_image_id')) {
            $program->images()->update(['is_cover' => false]);
            $program->images()->where('id', $coverId)->update(['is_cover' => true]);
        }

        // Új képek mentése
        $this->validateImages($request, $program);
        $this->saveImages($request, $program);

        return redirect()->route('admin.programs.edit', $program)
            ->with('success', 'Program sikeresen módosítva.');
    }

    public function destroy(Program $program): RedirectResponse
    {
        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program sikeresen törölve.');
    }

    private function validateImages(Request $request, Program $program): void
    {
        if (!$request->hasFile('images')) return;

        $existing = $program->images()->count();
        $deleting = count($request->input('delete_images', []));
        $remaining = $existing - $deleting;
        $maxNew = 7 - $remaining;

        $request->validate([
            'images'   => ['array', "max:{$maxNew}"],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:3072'],
        ], [
            'images.max'   => "Legfeljebb {$maxNew} új képet tölthetsz fel (max. 7 összesen).",
            'images.*.image' => 'Csak képfájl tölthető fel.',
            'images.*.max'   => 'Egy kép mérete legfeljebb 3 MB lehet.',
        ]);
    }

    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (
            Program::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function saveImages(Request $request, Program $program): void
    {
        if (!$request->hasFile('images')) return;

        $offset = $program->images()->count();

        foreach ($request->file('images') as $i => $file) {
            $path = $file->store("programs/{$program->id}", 'public');
            $program->images()->create([
                'path'       => $path,
                'is_cover'   => $offset === 0 && $i === 0,
                'sort_order' => $offset + $i,
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class AdminCountryController extends Controller
{
    public function index(): View
    {
        $countries = Country::withCount(['cities', 'programs'])
            ->orderBy('name->hu')
            ->paginate(20);

        return view('admin.countries.index', compact('countries'));
    }

    public function create(): View
    {
        return view('admin.countries.create');
    }

    public function store(CountryRequest $request): RedirectResponse
    {
        // A kép útvonalához az id kell: előbb a rekord, utána a fájl, egy tranzakcióban
        DB::transaction(function () use ($request) {
            $country = Country::create(['name' => $request->validated('name')]);

            if ($request->hasFile('image')) {
                $country->update(['image' => $this->storeImage($request->file('image'), $country)]);
            }
        });

        return redirect()->route('admin.countries.index')
            ->with('success', 'Ország sikeresen létrehozva.');
    }

    public function edit(Country $country): View
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(CountryRequest $request, Country $country): RedirectResponse
    {
        $data = ['name' => $request->validated('name')];
        $oldImage = $country->image;

        // Új kép és bejelölt eltávolítás együtt: az új kép nyer
        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'), $country);
        } elseif ($request->boolean('remove_image')) {
            $data['image'] = null;
        }

        $country->update($data);

        // A régi fájl csak sikeres mentés után törlődik
        if (array_key_exists('image', $data) && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('admin.countries.index')
            ->with('success', 'Ország sikeresen módosítva.');
    }

    public function destroy(Country $country): RedirectResponse
    {
        $cityCount = $country->cities()->count();

        if ($cityCount > 0) {
            return redirect()->route('admin.countries.index')
                ->with('error', "Az ország nem törölhető, mert {$cityCount} város tartozik hozzá. Előbb töröld vagy helyezd át a városait.");
        }

        // A képmappát a modell deleted eseménye törli
        $country->delete();

        return redirect()->route('admin.countries.index')
            ->with('success', 'Ország sikeresen törölve.');
    }

    private function storeImage(UploadedFile $file, Country $country): string
    {
        $path = $file->store("countries/{$country->id}", 'public');

        if ($path === false) {
            throw new RuntimeException('A kép mentése nem sikerült.');
        }

        return $path;
    }
}

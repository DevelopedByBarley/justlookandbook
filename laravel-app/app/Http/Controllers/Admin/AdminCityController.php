<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CityRequest;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class AdminCityController extends Controller
{
    public function index(Request $request): View
    {
        $countries = Country::orderBy('name->hu')->get();

        // A szűrő csak létező ország-id lehet; minden más értéket figyelmen kívül hagyunk
        $filter = $request->query('country');
        $selectedCountry = is_string($filter) && ctype_digit($filter)
            ? $countries->firstWhere('id', (int) $filter)
            : null;

        $cities = City::with('country')
            ->withCount('programs')
            ->when($selectedCountry, fn ($query) => $query->where('country_id', $selectedCountry->id))
            ->orderBy(Country::select('name->hu')->whereColumn('countries.id', 'cities.country_id'))
            ->orderBy('name->hu')
            ->paginate(20)
            ->withQueryString();

        return view('admin.cities.index', compact('cities', 'countries', 'selectedCountry'));
    }

    public function create(): View
    {
        $countries = Country::orderBy('name->hu')->get();

        return view('admin.cities.create', compact('countries'));
    }

    public function store(CityRequest $request): RedirectResponse
    {
        // A kép útvonalához az id kell: előbb a rekord, utána a fájl, egy tranzakcióban
        $city = DB::transaction(function () use ($request) {
            $city = City::create([
                'country_id' => $request->validated('country_id'),
                'name'       => $request->validated('name'),
            ]);

            if ($request->hasFile('image')) {
                $city->update(['image' => $this->storeImage($request->file('image'), $city)]);
            }

            return $city;
        });

        return redirect()->route('admin.cities.index', ['country' => $city->country_id])
            ->with('success', 'Város sikeresen létrehozva.');
    }

    public function edit(City $city): View
    {
        $countries = Country::orderBy('name->hu')->get();

        return view('admin.cities.edit', compact('city', 'countries'));
    }

    public function update(CityRequest $request, City $city): RedirectResponse
    {
        $data = [
            'country_id' => $request->validated('country_id'),
            'name'       => $request->validated('name'),
        ];
        $oldImage = $city->image;

        // Új kép és bejelölt eltávolítás együtt: az új kép nyer
        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'), $city);
        } elseif ($request->boolean('remove_image')) {
            $data['image'] = null;
        }

        $city->update($data);

        // A régi fájl csak sikeres mentés után törlődik
        if (array_key_exists('image', $data) && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('admin.cities.index', ['country' => $city->country_id])
            ->with('success', 'Város sikeresen módosítva.');
    }

    public function destroy(City $city): RedirectResponse
    {
        // A képmappát a modell deleted eseménye törli, a programok city_id-ja az FK miatt NULL lesz
        $city->delete();

        return redirect()->route('admin.cities.index', ['country' => $city->country_id])
            ->with('success', 'Város sikeresen törölve.');
    }

    private function storeImage(UploadedFile $file, City $city): string
    {
        $path = $file->store("cities/{$city->id}", 'public');

        if ($path === false) {
            throw new RuntimeException('A kép mentése nem sikerült.');
        }

        return $path;
    }
}

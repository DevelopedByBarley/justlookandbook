<?php

namespace App\Http\Requests\Admin;

use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'name.hu'    => ['required', 'string', 'max:100'],
            'name.en'    => ['required', 'string', 'max:100'],
            'name.it'    => ['nullable', 'string', 'max:100'],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:3072'],
        ];

        // Meglévő kép eltávolítása csak szerkesztéskor értelmezett
        if (! $this->isMethod('post')) {
            $rules['remove_image'] = ['nullable', 'boolean'];
        }

        return $rules;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $city = $this->route('city');

                if (! $city instanceof City || $validator->errors()->has('country_id')) {
                    return;
                }

                // Programos város országa nem cserélhető: a program városa a program országáé kell legyen
                if ((int) $this->input('country_id') !== $city->country_id) {
                    $programCount = $city->programs()->count();

                    if ($programCount > 0) {
                        $validator->errors()->add(
                            'country_id',
                            "Az ország nem módosítható, mert {$programCount} program tartozik a városhoz. Előbb vedd ki a városból a programokat."
                        );
                    }
                }
            },
        ];
    }

    public function attributes(): array
    {
        return [
            'country_id' => 'ország',
            'name.hu'    => 'magyar név',
            'name.en'    => 'angol név',
            'name.it'    => 'olasz név',
            'image'      => 'kép',
        ];
    }

    public function messages(): array
    {
        return [
            'country_id.required' => 'Válassz országot.',
            'country_id.integer'  => 'Válassz országot.',
            'country_id.exists'   => 'Válassz országot.',
            'name.*.required'     => 'A(z) :attribute megadása kötelező.',
            'name.*.string'       => 'A(z) :attribute szöveg kell legyen.',
            'name.*.max'          => 'A(z) :attribute legfeljebb :max karakter lehet.',
            'image.image'         => 'Csak képfájl tölthető fel.',
            'image.mimes'         => 'Csak képfájl tölthető fel.',
            'image.max'           => 'A kép mérete legfeljebb 3 MB lehet.',
            'image.uploaded'      => 'A kép feltöltése nem sikerült. A kép mérete legfeljebb 3 MB lehet.',
        ];
    }
}

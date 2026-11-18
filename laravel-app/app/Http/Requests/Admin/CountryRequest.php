<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name.hu' => ['required', 'string', 'max:100'],
            'name.en' => ['required', 'string', 'max:100'],
            'name.it' => ['nullable', 'string', 'max:100'],
            'image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:3072'],
        ];

        // Meglévő kép eltávolítása csak szerkesztéskor értelmezett
        if (! $this->isMethod('post')) {
            $rules['remove_image'] = ['nullable', 'boolean'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'name.hu' => 'magyar név',
            'name.en' => 'angol név',
            'name.it' => 'olasz név',
            'image'   => 'kép',
        ];
    }

    public function messages(): array
    {
        return [
            'name.*.required' => 'A(z) :attribute megadása kötelező.',
            'name.*.string'   => 'A(z) :attribute szöveg kell legyen.',
            'name.*.max'      => 'A(z) :attribute legfeljebb :max karakter lehet.',
            'image.image'     => 'Csak képfájl tölthető fel.',
            'image.mimes'     => 'Csak képfájl tölthető fel.',
            'image.max'       => 'A kép mérete legfeljebb 3 MB lehet.',
            'image.uploaded'  => 'A kép feltöltése nem sikerült. A kép mérete legfeljebb 3 MB lehet.',
        ];
    }
}

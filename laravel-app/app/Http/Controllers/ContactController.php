<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Support\ContactFailureNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Honeypot: a mező neve semleges, hogy a böngészők automatikus kitöltése ne töltse ki
        if ($request->filled('contact_hp')) {
            Log::info('Kapcsolati űrlap: honeypot kitöltve, üzenet eldobva.', [
                'ip'    => $request->ip(),
                'email' => $request->input('email'),
            ]);

            return back()->with('contact_success', true)->withFragment('contact');
        }

        $validator = Validator::make($request->all(), [
            'name'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email:rfc,filter', 'max:150'],
            'subject'       => ['nullable', 'string', 'max:255'],
            'message'       => ['required', 'string', 'max:2000'],
            'aszf_accepted' => ['accepted'],
        ], [
            'name.required'          => __('contact.validation.name_required'),
            'name.max'               => __('contact.validation.name_max'),
            'email.required'         => __('contact.validation.email_required'),
            'email.email'            => __('contact.validation.email_invalid'),
            'email.max'              => __('contact.validation.email_max'),
            'subject.max'            => __('contact.validation.subject_max'),
            'message.required'       => __('contact.validation.message_required'),
            'message.max'            => __('contact.validation.message_max'),
            'aszf_accepted.accepted' => __('contact.aszf_required'),
        ]);

        // Az űrlap az oldal alján van: hiba esetén oda kell visszaugrani, különben a látogató nem látja
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('_token', 'contact_hp'))
                ->withFragment('contact');
        }

        $data = $request->only('name', 'email', 'subject', 'message');

        try {
            Mail::to(config('mail.from.address'))->send(new ContactMail($data));
        } catch (\Throwable $e) {
            Log::error('Kapcsolati űrlap: az e-mail küldése sikertelen.', [
                'error' => $e->getMessage(),
                'data'  => $data,
            ]);

            ContactFailureNotifier::notify(ContactFailureNotifier::SEND_FAILED, $request, $e);

            return back()
                ->withInput($request->except('_token', 'contact_hp'))
                ->withErrors(['contact' => __('contact.send_failed')])
                ->withFragment('contact');
        }

        return back()->with('contact_success', true)->withFragment('contact');
    }
}

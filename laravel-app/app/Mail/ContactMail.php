<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Address as SymfonyAddress;

class ContactMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public readonly array $data
    ) {}

    public function envelope(): Envelope
    {
        $subject = ! empty($this->data['subject'])
            ? 'Érdeklődés: ' . $this->data['subject']
            : 'Új kapcsolatfelvétel: ' . $this->data['name'];

        return new Envelope(
            replyTo: $this->replyToAddress(),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            text: 'emails.contact-text',
        );
    }

    /**
     * A Symfony szigorúbban ellenőrzi a címet, mint a validáció. Ha a Reply-To nem
     * állítható be, a levél nélküle menjen ki, ne bukjon el miatta.
     */
    private function replyToAddress(): array
    {
        try {
            new SymfonyAddress($this->data['email']);
        } catch (\Throwable) {
            return [];
        }

        return [new Address($this->data['email'], $this->data['name'])];
    }
}

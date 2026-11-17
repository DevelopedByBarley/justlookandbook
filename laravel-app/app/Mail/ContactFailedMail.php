<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactFailedMail extends Mailable
{
    public function __construct(
        public readonly string $reason,
        public readonly array $data,
        public readonly array $meta,
    ) {}

    public function envelope(): Envelope
    {
        $who = ! empty($this->data['name']) ? $this->data['name'] : 'ismeretlen';

        return new Envelope(subject: '⚠ Sikertelen kapcsolatfelvétel a weboldalon: ' . $who);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-failed',
            text: 'emails.contact-failed-text',
        );
    }
}

{{-- Sima szöveges levél: nem HTML-ként jelenik meg, ezért nyersen írjuk ki, különben az & és ' entitásként látszana --}}
Egy látogató nem tudta elküldeni a kapcsolati űrlapot.
{!! $reason !!}

Név: {!! $data['name'] ?? '–' !!}
E-mail: {!! $data['email'] ?? '–' !!}
@if (!empty($data['subject']))
Program: {!! $data['subject'] !!}
@endif
Üzenet:
{!! $data['message'] ?? '–' !!}

Időpont: {!! $meta['time'] !!}
Oldal: {!! $meta['page'] ?? '–' !!}
Nyelv: {!! $meta['locale'] !!}
IP: {!! $meta['ip'] !!}
Böngésző: {!! $meta['user_agent'] ?? '–' !!}
@if (!empty($meta['error']))
Hibaüzenet: {!! $meta['error'] !!}
@endif

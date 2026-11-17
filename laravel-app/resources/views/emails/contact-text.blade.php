{{-- Sima szöveges levél: nem HTML-ként jelenik meg, ezért nyersen írjuk ki, különben az & és ' entitásként látszana --}}
Új kapcsolatfelvételi üzenet érkezett a weboldalon keresztül.

Név: {!! $data['name'] !!}
E-mail: {!! $data['email'] !!}
@if (!empty($data['subject']))
Program: {!! $data['subject'] !!}
@endif
Üzenet:
{!! $data['message'] !!}

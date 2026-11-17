<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #374151; }
        .label { font-weight: 600; color: #111827; }
        .alert { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; padding: 12px 16px; }
        .box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 20px; margin-top: 16px; }
        .meta { font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="alert">
        <p><strong>Egy látogató nem tudta elküldeni a kapcsolati űrlapot.</strong></p>
        <p>{{ $reason }}</p>
    </div>

    <p>Ha az alábbi adatok kitöltöttek, érdemes kézzel felvenni vele a kapcsolatot.</p>

    <div class="box">
        <p><span class="label">Név:</span> {{ $data['name'] ?? '–' }}</p>
        <p><span class="label">E-mail:</span> {{ $data['email'] ?? '–' }}</p>
        @if (!empty($data['subject']))
        <p><span class="label">Program:</span> {{ $data['subject'] }}</p>
        @endif
        <p><span class="label">Üzenet:</span></p>
        <p style="white-space: pre-line;">{{ $data['message'] ?? '–' }}</p>
    </div>

    <div class="box meta">
        <p>Időpont: {{ $meta['time'] }}</p>
        <p>Oldal: {{ $meta['page'] ?? '–' }}</p>
        <p>Nyelv: {{ $meta['locale'] }}</p>
        <p>IP: {{ $meta['ip'] }}</p>
        <p>Böngésző: {{ $meta['user_agent'] ?? '–' }}</p>
        @if (!empty($meta['error']))
        <p>Hibaüzenet: {{ $meta['error'] }}</p>
        @endif
    </div>
</body>
</html>

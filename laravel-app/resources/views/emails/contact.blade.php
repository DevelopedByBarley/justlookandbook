<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #374151; }
        .label { font-weight: 600; color: #111827; }
        .box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 20px; margin-top: 16px; }
    </style>
</head>
<body>
    <p>Új kapcsolatfelvételi üzenet érkezett a weboldalon keresztül.</p>

    <div class="box">
        <p><span class="label">Név:</span> {{ $data['name'] }}</p>
        <p><span class="label">E-mail:</span> {{ $data['email'] }}</p>
        @if (!empty($data['subject']))
        <p><span class="label">Program:</span> {{ $data['subject'] }}</p>
        @endif
        <p><span class="label">Üzenet:</span></p>
        <p style="white-space: pre-line;">{{ $data['message'] }}</p>
    </div>
</body>
</html>

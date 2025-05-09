<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Text Report' }}</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1 { font-size: 20px; }
        h2 { font-size: 18px; margin-top: 20px; }
        ul { margin-left: 20px; }
        li { margin-bottom: 10px; }
        strong { font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ $title ?? 'Title' }}</h1>

    <h2>Genel Değerlendirme:</h2>
    <p>{{ $summary ?? '' }}</p>

    <h2>Performans Değerlendirmesi:</h2>
    <ul>
        @foreach($performance ?? [] as $item)
            <li><strong>{{ $item['label'] }}:</strong> {{ $item['content'] }}</li>
        @endforeach
    </ul>

    <h2>Öneriler ve Gelişim Alanları:</h2>
    <ul>
        @foreach($suggestions ?? [] as $suggestion)
            <li>{{ $suggestion }}</li>
        @endforeach
    </ul>

    <p><strong>Sonuç:</strong> {{ $conclusion ?? '' }}</p>
</body>
</html>

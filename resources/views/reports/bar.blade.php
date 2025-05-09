<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bar Report</title>
    <style>
        body { font-family: sans-serif; }
        .progress-label {
            margin-bottom: 5px;
            font-size: 14px;
        }
        .progress-bar {
            width: 100%;
            background-color: #e5e5e5;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        .progress-fill {
            height: 12px;
            background-color: #8cb368;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <h2>Bar Report</h2>
    @foreach($bars as $bar)
        <div class="progress-label">{{ $bar['label'] }}</div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $bar['value'] }}%;"></div>
        </div>
    @endforeach
</body>
</html>

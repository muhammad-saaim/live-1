@php
    /** @var \App\Models\User $sharingUser */
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports shared with you</title>
    <style>
        .btn { display:inline-block; padding:10px 16px; background:#1f2937; color:#ffffff!important; text-decoration:none; border-radius:6px; }
        .container { max-width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; color:#111827; }
        .muted { color:#6b7280; font-size:12px; }
    </style>
    </head>
<body>
    <div class="container">
        <h2>Reports shared with you</h2>
        <p>
            The user <strong>{{ $sharingUser->name }}</strong> shared their reports and wants to contact with you.
        </p>
        <p>
            <a class="btn" href="{{ $mentorUrl }}">Open Mentor Page</a>
        </p>
        <p>
            You can view the client list on your Mentor page.
        </p>
        <p class="muted">Thanks,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>

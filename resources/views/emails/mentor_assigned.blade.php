<div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
    <p>Hi {{ $user->name }},</p>

    <p>
        Congratulations! Your account has been granted the <strong>Mentor</strong> role.
        You can now access your Mentor dashboard to view clients who share with you and their reports.
    </p>

    <p>
        Go to your Mentor page:
        <a href="{{ url(route('mentor.index')) }}">Open Mentor Dashboard</a>
    </p>

    <p>
        If you believe this was a mistake, please contact support.
    </p>

    <p>Thanks,<br>Matchology Team</p>
</div>



<div>
    <p>Hello {{ $name }},</p>
    <p>You recently requested to reset your password for your {{ env('APP_NAME') }} account. Click below to reset it</p>

    <a href="{{ $link }}" >Reset your password</a>

    <p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>

    <p>Thanks,<br>{{ env('APP_NAME') }}</p>
</div>
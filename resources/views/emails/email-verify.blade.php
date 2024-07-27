<div>
    <p>Hello {{ $name }},</p>
    <p>You are recently register into {{ env('APP_NAME') }}. Click below to verify account</p>

    <a href="{{ $link }}" >Verify</a>

    <p>If you did not register, please ignore this email or contact support if you have questions.</p>

    <p>Thanks,<br>{{ env('APP_NAME') }}</p>
</div>
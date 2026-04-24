<x-mail::message>
<div style="text-align:center; margin-bottom: 24px;">
    <div style="font-size: 28px; font-weight: 700; color: #7F64CE;">
        UniPath
    </div>
</div>

# Hello, {{ $notifiable->name ?? 'there' }}

You are receiving this email because we received a password reset request for your UniPath account.

Click the button below to reset your password:

<x-mail::button :url="$actionUrl" color="primary">
Reset Password
</x-mail::button>

This password reset link will expire in 60 minutes.

If you did not request a password reset, no further action is required.

Thanks,<br>
UniPath Team

<x-slot:subcopy>
If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:

<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
</x-mail::message>
@component('mail::message')
Activate your Account

Thinks for signing up, now u can activate your account.
Click the button.

@component('mail::button', ['url' => route('activate', [
            'token' => $user -> token,
            'email' => $user -> email
           ])
           ])
    Activate
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

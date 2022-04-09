@component('mail::message')
# Hello dear user

This is a simple test message.

@component('mail::button', ['url' => 'https://google.com'])
    Google
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

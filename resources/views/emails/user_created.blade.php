@component('mail::message')
# Hi {{ $user->first_name ?? $user->email }},

{{ Lang::get('Please save your account credentials to access the app. Here is your account password:') }}

@component('mail::panel')
# {{ $password }}
@endcomponent

The {{ config('app.name') }} Team. <br>
@endcomponent

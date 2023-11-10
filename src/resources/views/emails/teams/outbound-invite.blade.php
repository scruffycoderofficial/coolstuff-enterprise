@component('mail::message')
# {{ $team->owner->name }} has invited you to join {{ $team->name }} team.

You have been invited to the **{{ $team->name }}** team.

To join {{ $team->name }}, click on the register button below to create your own account. Once registered you can accept the invitation from {{ $team->owner->first_name }} by going to your profile and clicking on the accept button.

@component('mail::button', ['url' => route('register')])
    Register
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent

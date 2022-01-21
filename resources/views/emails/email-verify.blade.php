@component('mail::message')
# Welcome {{ucfirst($user->first_name)}},
Please verify your email address so we know it's really you, and so we can send you important information about your TimeToGive account.

@component('mail::button', [
  'url' => route('verify', ['token' => $user->email_verify_token]),
  'color'=>'green'
  ]
)
Verify Email Address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

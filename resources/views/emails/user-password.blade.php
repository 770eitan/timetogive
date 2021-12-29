@component('mail::message')
# Welcome {{ucfirst($user->first_name)}},
Thanks for verify your account with {{config('app.name')}}. Please check your password below

<p>Password: <strong>{{$plainPass}}</strong></p>

<p>Charity Code: <strong>{{$user->charity_ticker->charity_code}}</strong></p>

<p>We have a generated a unique link to your charity, please click on below button to visit the page.</p>

@component('mail::button', [
  'url' => route('charity', ['charity_code' => $user->charity_ticker->charity_code]),
  'color'=>'green'
  ]
)
View Charity
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

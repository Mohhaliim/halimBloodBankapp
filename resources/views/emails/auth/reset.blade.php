@component('mail::message')
# Introduction

Blood Bank password reset.

@component('mail::button', ['url' => 'http://127.0.0.1:8000/api/v1/newpassword'])
Reset
@endcomponent

<p>Your reset code is : {{$code}}</p>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
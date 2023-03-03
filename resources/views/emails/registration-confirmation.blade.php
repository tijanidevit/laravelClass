@component('mail::message')
# Registration Confirmation
Name: {{$user->name}}<br>
Email:{{$user->email}}<br>
Pasword:{{$user->pasword}}



@endcomponent

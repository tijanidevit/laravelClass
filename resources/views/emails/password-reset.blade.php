@component('mail::message')
# Introduction

The body of your message.
Token: {{$user->token}}


@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

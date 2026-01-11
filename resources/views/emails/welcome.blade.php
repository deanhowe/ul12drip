<x-mail::message>
# Welcome, {{ $user->name }}!

Thank you for joining our application. We are excited to have you on board.

<x-mail::button :url="config('app.url')">
Visit our Website
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

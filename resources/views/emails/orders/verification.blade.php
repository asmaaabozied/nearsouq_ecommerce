@component('mail::message')
# {{$data["body"]}}


Hello <stong> {{$data["name"]}}</strong>
<p> {{$data["body"]}}</p>
@component('mail::button', ['url' => $data["button"]['url']])
{{$data["button"]["button_name"]}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

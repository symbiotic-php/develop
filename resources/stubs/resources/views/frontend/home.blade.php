@extends('frontend/layout')
{{{$title = 'Главная страница приложения #APP_NAME#'}}}
{{-- Записываем в секцию макета, там будет выведено через @ yield('title') --}}
@section('title')
    {{$title}}
@stop
<h3>Контент шаблона #APP_ID#/resources/views/frontend/index.blade.php</h3>
<p>Сейчас я такое расскажу...<br>
    Блин, так. С чего начать-то?<br>
    А, вот с чего.<br>
    Это - город, в котором я живу.<br>
    Хотя, на самом деле я живу не здесь...<br>
    я живу вот здесь.<br>
    Тут у меня всякие программки.<br>
    Здесь я гуляю. Здесь - общаюсь.<br>
    Так я узнаю о погоде на улице.<br>
    Программка называется:<br>
    "Впадлу встать и в окно посмотреть"...
</p>
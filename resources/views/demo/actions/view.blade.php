@extends('demo/layouts/layout')
{{{$title = 'Demo controller'}}}
{{-- Записываем в секцию макета, там будет выведено через @ yield('title') --}}
@section('title')
{{$title}}
@stop
<h2>{{$title}}</h2>
<div class="section">
    <div class="row">
        <div class="col-md-12 col-sm-12"><h3>Контроллер:</h3></div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Класс:</b></div>
        <div class="col-sm-9 col-md-9">{{$controller['class']}}</div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Метод:</b></div>
        <div class="col-sm-9 col-md-9">{{$controller['method']}}</div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Template:</b></div>
        <div class="col-sm-9 col-md-9">{{$controller['view']}}</div>
    </div>
</div>

<div class="section">
    <div class="row">
        <div class="col-md-12 col-sm-12"><h3>Роут:</h3></div>
    </div>
    <?php
    /**
     * @var \Dissonance\Contracts\Routing\RouteInterface $route
     */
    ?>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Домен:</b></div>
        <div class="col-sm-9 col-md-9">"{{$route->getDomain()}}"</div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Паттерн:</b></div>
        <div class="col-sm-9 col-md-9">"{{$route->getPath()}}"</div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Имя:</b></div>
        <div class="col-sm-9 col-md-9">"{{$route->getName()}}"</div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Мидлвары:</b></div>
        <div class="col-sm-9 col-md-9">
            @foreach($route->getMiddlewares() as $v)
                "{{$v}}"<br>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Обработчик:</b></div>
        <div class="col-sm-9 col-md-9">"{{$route->getHandler()}}"</div>
    </div>
</div>

<div class="section">
    <?php
    /**
     * @var  \Dissonance\Contracts\App\ApplicationInterface $app
     */
    ?>
    <div class="row">
        <div class="col-md-12 col-sm-12"><h3>Приложение:</h3></div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Id:</b></div>
        <div class="col-sm-9 col-md-9">"{{$app->getId()}}"</div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Name:</b></div>
        <div class="col-sm-9 col-md-9">"{{$app->getAppName()}}"</div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Title:</b></div>
        <div class="col-sm-9 col-md-9">"{{$app->getAppTitle()}}"</div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3"><b>Parent app:</b></div>
        <div class="col-sm-9 col-md-9">"{{$app->getParentAppId()}}"</div>
    </div>
        <div class="row">
            <div class="col-sm-3 col-md-3"><b>Routing provider:</b></div>
            <div class="col-sm-9 col-md-9">"{{$app->getRoutingProvider()}}"</div>
        </div>
</div>
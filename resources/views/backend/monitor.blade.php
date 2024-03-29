@extends('develop::backend/layout')
<?php
/**
 * @var \Symbiotic\Develop\Services\Monitoring\PackagesInfo $packages_monitor
 * @var \Symbiotic\Develop\Services\Debug\Timer             $timer
 */
?>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <h2>Сводка</h2>
    </div>
    <div class="col-md-12 col-sm-12">
        <h3>Пакеты</h3>
    </div>
    <div class="col-sm-12 col-md-6 row">
        <div class="col-sm-8 col-md-6">Всего пакетов:</div>
        <div class="col-sm-4 col-md-6">{{$packages_monitor->getCountPackages()}}</div>
        <div class="col-sm-8 col-md-6">Пакетов с расширением ядра:</div>
        <div class="col-sm-4 col-md-6">{{$packages_monitor->getCountCorePackages()}}</div>
        <div class="col-sm-8 col-md-6">Пакетов приложений:</div>
        <div class="col-sm-4 col-md-6">{{$packages_monitor->getCountApps()}}</div>
        <div class="col-sm-8 col-md-6">Приложений с плагинами:</div>
        <div class="col-sm-4 col-md-6">{{$packages_monitor->getCountAppsWithPlugins()}}</div>
        <div class="col-sm-8 col-md-6">Пакеты только со статикой и шаблонами:</div>
        <div class="col-sm-4 col-md-6">{{$packages_monitor->getCountStaticPackages()}}</div>
    </div>
    <div class="col-md-12 col-sm-12">
        <a class="button primary"
           href="{{$this->route('backend:develop::PackagesBuilding.PackagesCreator.test_packages')}}">Создать тестовые
            приложения</a>
        <a class="button " href="{{$this->route('backend:develop::PackagesBuilding.PackagesCreator.test_delete')}}">Удалить
            тестовые приложения</a>
    </div>
    <div class="col-md-12 col-sm-12">
        <h3>Ядро</h3>
        <p><small>Все расширения создают нагрузку при каждом запросе</small></p>
    </div>
    <div class="col-sm-12 col-md-6 row">
        <div class="col-sm-8 col-md-6">Бутстраперов:</div>
        <div class="col-sm-4 col-md-6">{{$packages_monitor->getCountBootstraps()}}</div>
        <div class="col-sm-8 col-md-6">Корневых провайдеров:</div>
        <div class="col-sm-4 col-md-6">{{$packages_monitor->getCountCoreProviders()}}</div>
        <div class="col-sm-8 col-md-6">Отложенных провайдеров:</div>
        <div class="col-sm-4 col-md-6">{{$packages_monitor->getCountDeferServices()}}</div>
    </div>
    <div class="col-md-12 col-sm-12">
        <h3>Ключевые компоненты</h3>
    </div>
    <div class="col-sm-12 col-md-8 row">
        <div class="col-sm-6 col-md-6">Core</div>
        <div class="col-sm-4 col-md-6">
            {{get_class($this->core())}}
        </div>
        <div class="col-sm-6 col-md-6">Routing</div>
        <div class="col-sm-4 col-md-6">
            {{get_class($this->core(\Symbiotic\Routing\RouterInterface::class))}}
        </div>
        <div class="col-sm-6 col-md-6">Session</div>
        <div class="col-sm-4 col-md-6">
            Driver ({{$this->core(\Symbiotic\Session\SessionManagerInterface::class)->getDefaultDriver()}})
            {{get_class($this->core(\Symbiotic\Session\SessionStorageInterface::class))}}
        </div>
        <div class="col-sm-6 col-md-6">Cache</div>
        <div class="col-sm-4 col-md-6">
            {{get_class($this->core(\Psr\SimpleCache\CacheInterface::class))}}
        </div>
        <div class="col-sm-6 col-md-6">Базы данных</div>
        <div class="col-sm-4 col-md-6">
            @if($this->core()->has(\Symbiotic\Database\DatabaseManager::class))
                symbiotic/database - Базовый пакет подключений<br>
            @endif
            @if($this->core()->has(\Symbiotic\Database\Eloquent\EloquentManager::class))
                    symbiotic/eloquent - Laravel ORM
            @endif
        </div>
    </div>
    <div class="col-md-12 col-sm-12">
        <h3>Timers</h3>
    </div>
    <div class="col-sm-12 col-md-8 row">
        @foreach($timer->getTimers() as $v)
            <div class="col-sm-6 col-md-6">{{substr($v['name'],-40)}}</div>
            <div class="col-sm-4 col-md-6">{{$v['time']}} mem: {{$v['memory']}}</div>
        @endforeach
    </div>
    <div class="col-md-12 col-sm-12">
        <h3>Память</h3>
    </div>
    <div class="col-sm-12 col-md-8 row">

        <div class="col-sm-6 col-md-6">Пиковая</div>
        <div class="col-sm-4 col-md-6">{{ \Symbiotic\Develop\Services\Debug\Timer::readableSize(memory_get_peak_usage())}}</div>
        <div class="col-sm-6 col-md-6">Фактическая</div>
        <div class="col-sm-4 col-md-6">{{ \Symbiotic\Develop\Services\Debug\Timer::readableSize(memory_get_usage())}}</div>
        <div class="col-sm-6 col-md-6">Выделенная</div>
        <div class="col-sm-4 col-md-6">{{ \Symbiotic\Develop\Services\Debug\Timer::readableSize(memory_get_usage(true))}}</div>
    </div>
    <div class="col-md-12 col-sm-12">
        <a href="{{ $this->route('backend:develop::monitor.timer') }}" target="_blank">
            <h3>Тайминг в апи</h3>
        </a>
        <a href="{{ $this->route('backend:develop::monitor.apps_memory') }}" target="_blank">
            <h3>Память при всех загруженных приложениях</h3>
        </a>
    </div>
</div>
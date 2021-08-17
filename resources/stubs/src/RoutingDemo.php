<?php


namespace DummyPackageNamespace;


use Dissonance\Contracts\Routing\RouterInterface;
use Dissonance\Routing\AppRouting;


class DummyClass extends AppRouting
{

    /**
     * Админские роуты приложения
     *
     * Работают в префиксе /framework_root/backend/#APP_ID#/
     * и защищены Миддлварой авторизации (еще не сделано)
     *  для поиска роута route('backend:#APP_ID#::index') {@see \_DS\route()}
     *
     *
     * @param RouterInterface $router
     */
    public function backendRoutes(RouterInterface $router)
    {
        /**
         * @uri /framework_root/backend/#APP_ID#/ - корневой екшен приложения
         * @uses \DummyPackageNamespace\Http\Controllers\Backend\Index::index()
         */
        $router->get('/', [
            'uses' => 'Backend\\Index@index',
            'as' => 'index'
        ]);

        /**
         * @uri /framework_root/backend/#APP_ID#/test/
         * @uses \DummyPackageNamespace\Http\Controllers\Backend\Index::test()
         */
        $router->get('/test', [
            'uses' => 'Backend\\Index@test',
            'as' => 'test'
        ]);
    }

    /**
     * Публичные роуты приложения
     *
     * Работают в префиксе /framework_root/#APP_ID#/
     * для поиска роута route('#APP_ID#::home') {@see \_DS\route()}
     *
     * @param RouterInterface $router
     */
    public function frontendRoutes(RouterInterface $router)
    {
        /**
         * @uri /framework_root/#APP_ID#/ - корневой екшен приложения
         * @uses \DummyPackageNamespace\Controllers\Http\Frontend\Index::index()
         */
        $router->get('/', [
            'uses' => 'Index@index',
            'as' => 'home',
        ]);

        /**
         * @uri /framework_root/#APP_ID#/array/  json ответ
         * @uses \DummyPackageNamespace\Controllers\Http\Frontend\Index::array()
         */
        $router->get('array', [
            'uses' => 'Index@array',
            'as' => 'array',
        ]);

        /**
         * @uri /framework_root/#APP_ID#/file/  контент файла с заголовком мим типа
         * @uses \DummyPackageNamespace\Controllers\Http\Frontend\Index::file()
         */
        $router->get('file', [
            'uses' => 'Index@file',
            'as' => 'file',
        ]);

        /**
         * @uri /framework_root/#APP_ID#/download/  загрузка файла
         * @uses \DummyPackageNamespace\Controllers\Http\Frontend\Index::download()
         */
        $router->get('download', [
            'uses' => 'Index@download',
            'as' => 'download',
        ]);

        /**
         * @uri /framework_root/#APP_ID#/error404/  404 ошибка
         * @uses \DummyPackageNamespace\Http\Controllers\Frontend\Index::error404()
         */
        $router->get('error404', [
            'uses' => 'Index@error404',
            'as' => 'error404',
        ]);

    }

    /**
     * Работают в корне фремворка /framework_root/

     * для поиска роута по имени route('default::#APP_ID#.route_name') {@see \_DS\route()}
     *
     * @param RouterInterface $router
     */
    public function defaultRoutes(RouterInterface $router)
    {
        /**
         * @uri /framework_root/default_d2r4334tf3fd34rdd23dd33d3/ роут от корня фреймоворка
         * @route("default::#APP_ID#.md5_route")
         *
         * @uses \DummyPackageNamespace\Http\Controllers\Frontend\Index::default()
         */
        $router->get('default_'.\md5($this->app->getId()), [
            'uses' => 'Index@app_md5',
            'as' => 'md5_route',
        ]);
    }
}
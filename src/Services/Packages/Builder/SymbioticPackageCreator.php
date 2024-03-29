<?php

namespace Symbiotic\Develop\Services\Packages\Builder;

use Symbiotic\Core\Support\Str;
use function dirname;
use function trim;
use const _S\DS;

/**
 * Class SymbioticPackageCreator
 * @package Symbiotic\Develop\Services\Packages\Builder
 */
class SymbioticPackageCreator extends StaticPackageCreator implements AppPackageInterface, CorePackageInterface
{

    const NAMESPACE_STUB_KEY = 'DummyNamespace';

    const NAMESPACE_PACKAGE_STUB_KEY = 'DummyPackageNamespace';

    const CLASS_STUB_KEY = 'DummyClass';

    /**
     * Базовый неймспейс
     * Пишется без начального и конечного слеша 'VendorName\PackageName'
     *
     * @see SymbioticPackageCreator::getBaseNamespace()
     * @see SymbioticPackageCreator::setBaseNamespace()
     * @var string|null
     */
    protected ?string $base_namespace = null;

    protected bool $with_app = true;

    protected bool $with_app_providers = false;

    protected bool $with_app_container = false;

    /**
     * @var bool Глобальный флаг генерации контроллеров
     * Если false, то не будет создан роутинг, контроллеры, шаблоны контроллеров
     */
    protected bool $with_app_controllers = true;

    protected bool $with_app_backend_controllers = true;

    protected bool $with_app_frontend_controllers = true;

    protected bool $with_core_bootstrap = false;

    protected bool $with_core_provider = false;

    protected bool $with_demo = false;


    public function setBaseNamespace(string $namespace):static
    {
        $this->base_namespace = trim($namespace, '\\/');

        return $this;
    }

    public function withBootstrap(): static
    {
        $this->with_core_bootstrap = true;
        return $this;
    }

    public function withCoreProviders(): static
    {
        $this->with_core_provider = true;
        return $this;
    }

    public function withOutBootstrap(): static
    {
        $this->with_core_bootstrap = false;
        return $this;
    }

    public function withOutCoreProvider(): static
    {
        $this->with_core_provider = false;
        return $this;
    }

    public function withAppProviders(): static
    {
        $this->with_app_providers = true;
        return $this;
    }

    public function withApplicationContainer():static
    {
        $this->with_app_container = true;
        return $this;
    }

    public function withOutApp(): static
    {
        $this->with_app = false;

        return $this;
    }

    public function withOutBackend(): static
    {
        $this->with_app_backend_controllers = false;

        return $this;
    }

    public function withOutFrontend(): static
    {
        $this->with_app_frontend_controllers = false;
        return $this;
    }

    public function withOutControllers(): static
    {
        $this->with_app_controllers = false;

        return $this;
    }

    public function withDemo(): static
    {
        $this->with_demo = true;
        return $this;
    }

    protected function makePackageFiles()
    {
        if ($this->with_app) {
            /**
             * "routing": "\\Symbiotic\\App\\Filesmanager\\Routing",
             * "controllers_namespace": "\\Symbiotic\\App\\Filesmanager\\Controllers",
             * "version": "1.0.0",
             * "requires": [],
             * "providers": [
             * "\\Symbiotic\\App\\Filesmanager\\Providers\\AppProvider"
             * ]
             */
            $this->symbiotic_package_config['app'] = [
                'id' => $this->package_id,
            ];
            if ($this->parent_app_id) {
                $this->symbiotic_package_config['app']['parent_app'] = $this->parent_app_id;
            }
            $this->createAssets();
            if ($this->with_app_controllers) {
                $this->symbiotic_package_config['app']['controllers_namespace'] = '\\' . $this->getBaseNamespace() . '\\Http\\Controllers';
                if ($this->with_app_frontend_controllers) {
                    $this->createFrontendControllers();
                }
                if ($this->with_app_backend_controllers) {
                    $this->createBackendControllers();
                }
                if ($this->with_app_providers) {
                    $this->createAppProviders();
                }
                if ($this->with_app_backend_controllers || $this->with_app_frontend_controllers) {
                    $this->createAppRouting();
                }
                if($this->with_demo) {
                    $this->createDemoResources();
                }
            }

            if ($this->with_app_container) {
                $this->createApplicationClass();
            }
        }
        // Core
        if ($this->with_core_bootstrap) {
            $this->createBootstrap();
        }
        if ($this->with_core_provider) {
            $this->createCoreProvider();
        }
        $this->composer['autoload']->{'psr-4'} = [
            $this->getBaseNamespace() . '\\' => 'src/'
        ];
    }


    protected function createBackendResources()
    {

        $files = [
            'resources/views/backend/layout.blade.php' => 'resources/views/backend/layout.blade.php',
            'resources/views/backend/index.blade.php' => 'resources/views/backend/index.blade.php'
        ];

        $this->createFiles($files);
    }

    protected function createDemoResources()
    {

        $files = [
            'resources/views/demo/layout.blade.php' => 'resources/views/demo/layout.blade.php',
            'resources/views/demo/backend.blade.php' => 'resources/views/demo/backend.blade.php',
            'resources/views/demo/index.blade.php' => 'resources/views/demo/index.blade.php',
            'resources/views/demo/services.blade.php' => 'resources/views/demo/services.blade.php'
        ];

        $this->createFiles($files);
    }
    protected function createFrontendResources()
    {

        $files = [
            'resources/views/frontend/layout.blade.php' => 'resources/views/frontend/layout.blade.php',
            'resources/views/frontend/home.blade.php' => 'resources/views/frontend/home.blade.php',
            'resources/views/frontend/errors/error.blade.php' => 'resources/views/frontend/errors/error.blade.php',
            'resources/views/frontend/errors/exception.blade.php' => 'resources/views/frontend/errors/exception.blade.php',
        ];

        $this->createFiles($files);
    }

    protected function getSrcPackagePath(string $path = null)
    {
        return $this->getPackagePath('src' . (!empty($path) ? DS . ltrim($path, '\\/') : ''));
    }

    protected function createBackendControllers()
    {
        $this->createBackendResources();
        $path_prefix = 'src/Http/Controllers/Backend';
        $this->createClassesFiles([
            $path_prefix . '/Controller' . ($this->with_demo ? 'Demo' : '') . '.php' => $path_prefix . '/Index.php'
        ]);

    }

    protected function createFrontendControllers()
    {
        $this->createFrontendResources();
        $path_prefix = 'src/Http/Controllers/Frontend';
        $this->createClassesFiles([
            $path_prefix . '/Controller' . $this->getDemoString() . '.php' => $path_prefix . '/Index.php'
        ]);
    }

    protected function getDemoString(): string
    {
        return ($this->with_demo ? 'Demo' : '');
    }

    protected function createBootstrap()
    {
        $this->symbiotic_package_config['bootstrappers'] = ['\\' . $this->getBaseNamespace() . '\\Bootstrap\\Bootstrap'];
        $this->createClassesFiles([
            'src/Bootstrap/Bootstrap' . $this->getDemoString() . '.php' => 'src/Bootstrap/Bootstrap.php'
        ]);

    }

    protected function createApplicationClass()
    {
        $this->symbiotic_package_config['app']['app_class'] = '\\' . $this->getBaseNamespace() . '\\MyAppContainer';
        $this->createClassesFiles([
            'src/Application.php' => 'src/MyAppContainer.php'
        ]);
    }
    protected function createAppProviders()
    {
        $this->symbiotic_package_config['app']['providers'] = ['\\' . $this->getBaseNamespace() . '\\Providers\\AppProvider'];
        $this->createClassesFiles([
            'src/Providers/AppProvider' . $this->getDemoString() . '.php' => 'src/Providers/AppProvider.php'
        ]);
        if($this->with_demo) {
            $this->createClassesFiles(
                [
                 'src/Services/LiveService.php' => 'src/Services/LiveService.php',
                 'src/Services/CloningService.php' => 'src/Services/CloningService.php',
                 'src/Services/Singleton.php' => 'src/Services/Singleton.php'
                ]
            );
        }
    }

    protected function createAppRouting()
    {
        $this->symbiotic_package_config['app']['routing'] = '\\' . $this->getBaseNamespace() . '\\Routing';
        $this->createClassesFiles([
            'src/Routing' . $this->getDemoString() . '.php' => 'src/Routing.php'
        ]);
    }

    protected function createCoreProvider()
    {
        $this->symbiotic_package_config['providers'] = ['\\' . $this->getBaseNamespace() . '\\Providers\\CoreProvider'];
        $this->createClassesFiles([
            'src/Providers/CoreProvider' . $this->getDemoString() . '.php' => 'src/Providers/CoreProvider.php'
        ]);
    }

    protected function createClassesFiles(array $files)
    {

        $root = $this->getPackageRootPath();
        foreach ($files as $stub_path => $file_path) {
            $base_namespace = $this->getBaseNamespace();
            $namespace = str_replace('/', '\\', preg_replace('@^src[/\\\]@', '', dirname($file_path)));
            $namespace = $base_namespace . '\\' . trim($namespace, '/\\');
            $class = preg_replace('@.php$@', '', basename($file_path));
            $replaces = [
                static::NAMESPACE_STUB_KEY => $namespace,
                static::NAMESPACE_PACKAGE_STUB_KEY => $base_namespace,
                static::CLASS_STUB_KEY => $class,
            ];

            $this->createFile($root . DS . ltrim($file_path, '\\/'), $this->getStubClassContent($stub_path, $replaces));
        }
    }

    protected function getStubClassContent(string $path, array $replaces):string
    {
        $replaces = array_merge($this->getStubReplaces(), $replaces);
        return $this->getStubFileContent($path, array_merge($this->getStubReplaces(), $replaces));
    }


    protected function getBaseNamespace(): string
    {
        if (!$this->base_namespace) {
            $this->base_namespace = 'Symbiotic\\Module\\' . \ucfirst(Str::camel($this->package_id));
        }

        return $this->base_namespace;
    }


}
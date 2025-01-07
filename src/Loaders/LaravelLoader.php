<?php

namespace TweakPHP\Client\Loaders;

use Psy\Configuration;
use Throwable;

class LaravelLoader extends ComposerLoader
{
    private $app;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct($path);
        $this->app = require_once $path . '/bootstrap/app.php';
        $this->app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        $classAliases = require $path . '/vendor/composer/autoload_classmap.php';
        $vendorPath = dirname($path . '/vendor/composer/autoload_classmap.php', 2);
        foreach ($classAliases as $class => $path) {
            if (!str_contains($class, '\\')) {
                continue;
            }
            if (str_starts_with($path, $vendorPath)) {
                continue;
            }
            try {
                class_alias($class, class_basename($class));
            } catch (Throwable $e) {
            }
        }
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Laravel';
    }

    /**
     * @return string
     */
    public function version(): string
    {
        return $this->app->version();
    }

    /**
     * @param Configuration $config
     * @return void
     */
    public function configure(Configuration $config): void
    {
        if (class_exists('Illuminate\Support\Collection') && class_exists('Laravel\Tinker\TinkerCaster')) {
            $config->getPresenter()->addCasters([
                \Illuminate\Support\Collection::class => 'Laravel\Tinker\TinkerCaster::castCollection',
            ]);
        }
        if (class_exists('Illuminate\Database\Eloquent\Model') && class_exists('Laravel\Tinker\TinkerCaster')) {
            $config->getPresenter()->addCasters([
                \Illuminate\Database\Eloquent\Model::class => 'Laravel\Tinker\TinkerCaster::castModel',
            ]);
        }
        if (class_exists('Illuminate\Foundation\Application') && class_exists('Laravel\Tinker\TinkerCaster')) {
            $config->getPresenter()->addCasters([
                \Illuminate\Foundation\Application::class => 'Laravel\Tinker\TinkerCaster::castApplication',
            ]);
        }
    }
}

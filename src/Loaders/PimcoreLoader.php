<?php

declare(strict_types=1);

namespace TweakPHP\Client\Loaders;

class PimcoreLoader extends BaseLoader
{
    /**
     * @param string $path
     * @return bool
     */
    public static function supports(string $path): bool
    {
        return file_exists($path . '/vendor/autoload.php') && file_exists($path . '/vendor/pimcore/pimcore');
    }

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        // Include the Composer autoloader
        require_once $path . '/vendor/autoload.php';

        \Pimcore\Bootstrap::setProjectRoot();
        \Pimcore\Bootstrap::bootstrap();
        \Pimcore\Bootstrap::kernel();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Pimcore';
    }

    /**
     * @return string
     */
    public function version(): string
    {
        return \Composer\InstalledVersions::getPrettyVersion('pimcore/pimcore');
    }

    public function variables(): array
    {
        $scope = [
            'kernel' => \Pimcore::getKernel(),
        ];

        if (isset($scope['kernel'])) {
            $scope['container'] = $scope['kernel']->getContainer();
            if (isset($scope['container']) && $scope['container']->has('test.service_container')) {
                $scope['container'] = $scope['container']->get('test.service_container');
            }
        }

        return $scope;
    }


}

<?php

namespace TweakPHP\Client\Loaders;

use Psy\Configuration;

interface LoaderInterface
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function version(): string;

    /**
     * @return void
     */
    public function init();

    /**
     * @param string $code
     * @return void
     */
    public function execute(string $code);

    /**
     * @param Configuration $config
     * @return void
     */
    public function configure(Configuration $config): void;

    /**
     * @return array
     */
    public function variables(): array;
}

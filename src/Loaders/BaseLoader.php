<?php

namespace TweakPHP\Client\Loaders;

use Psy\Configuration;
use Psy\VersionUpdater\Checker;
use TweakPHP\Client\OutputModifiers\CustomOutputModifier;
use TweakPHP\Client\Tinker;

abstract class BaseLoader implements LoaderInterface
{
    protected Tinker $tinker;

    public function init()
    {
        $config = new Configuration([
            'configFile' => null,
        ]);
        $config->setUpdateCheck(Checker::NEVER);
        $this->configure($config);
        $config->setRawOutput(true);

        $this->tinker = new Tinker(new CustomOutputModifier(), $config, $this->variables());
    }

    public function execute(string $code)
    {
        $output = $this->tinker->execute($code);

        echo trim($output);
    }

    /**
     * @param Configuration $config
     * @return void
     */
    public function configure(Configuration $config): void
    {
    }

    /**
     * @return array
     */
    public function variables(): array
    {
        return [];
    }
}
